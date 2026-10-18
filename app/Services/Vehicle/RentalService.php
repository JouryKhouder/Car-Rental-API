<?php

namespace App\Services\Vehicle;

use App\Exceptions\BookingException;
use App\Mail\Payment\PaymentReceiptMail;
use App\Models\Customer;
use App\Models\RentalBooking;
use App\Models\RentalTransaction;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\Rental\VehicleRentalConfirmationMail;

class RentalService
{
    public function createBooking(array $data, int $userId): RentalBooking
    {
        $customer = $this->getAuthenticatedCustomer($userId);

        return DB::transaction(function () use ($data, $customer) {

            $vehicle = $this->lockAndValidateVehicle(
                $data['vehicle_id'],
                $data['rental_start_date'],
                $data['rental_end_date']
            );

            $booking = $this->createBookingRecord($vehicle, $customer->id, $data);

            $requiredDeposit = $booking->initial_total_due_amount / 2;

            if ($data['paid_amount'] < $requiredDeposit) {
                throw new BookingException("Sorry, at least 50% must be paid as an initial payment. Amount required: $" . $requiredDeposit);
            }
            // ---------------------------------------------------------

            Mail::to($customer->user->email)->send(new VehicleRentalConfirmationMail($customer->user, $booking, $vehicle));

            $payment = $this->processInitialPayment($booking, $data);
            Mail::to($customer->user->email)->send(new PaymentReceiptMail($customer->user, $payment, $booking, $vehicle));

            $this->updateVehicleStatusIfStarted($vehicle, $data['rental_start_date']);
            $customer->increment('rental_count');

            return $booking->load(['vehicle.carModel.make', 'customer.user']);
        });
    }


    private function getAuthenticatedCustomer(int $userId): Customer
    {
        $customer = Customer::where('user_id', $userId)->first();

        if (!$customer) {
            throw new BookingException('You must complete the customer\'s account details before booking a car.', 403);
        }

        return $customer;
    }

    private function lockAndValidateVehicle(int $vehicleId, string $startDate, string $endDate): Vehicle
    {
        $vehicle = Vehicle::whereKey($vehicleId)->lockForUpdate()->firstOrFail();

        if ($vehicle->status === 'maintenance') {
            throw new BookingException('This vehicle is under maintenance and is not available for booking.', 409);
        }

        if ($this->hasOverlap($vehicle->id, $startDate, $endDate)) {
            throw new BookingException('The vehicle is already booked for the requested period. Please choose different dates or another vehicle.', 409);
        }

        return $vehicle;
    }

    private function createBookingRecord(Vehicle $vehicle, int $customerId, array $data): RentalBooking
    {
        $booking = new RentalBooking();
        $booking->forceFill([
            'vehicle_id' => $vehicle->id,
            'customer_id' => $customerId,
            'rental_start_date' => $data['rental_start_date'],
            'rental_end_date' => $data['rental_end_date'],
            'pickup_location' => $data['pickup_location'],
            'dropoff_location' => $data['dropoff_location'],
            'rental_price_per_day' => $vehicle->rental_price_per_day,
            'initial_check_notes' => $data['initial_check_notes'] ?? null,
        ])->save();

        return $booking->refresh();
    }

    private function processInitialPayment(RentalBooking $booking, array $data): RentalTransaction
    {
        return RentalTransaction::create([
            'booking_id' => $booking->id,
            'payment_method' => $data['payment_method'] ?? 'cash',
            'payment_type' => 'deposit',
            'amount' => $data['paid_amount'],
            'transaction_date' => now(),
        ]);
    }

    private function updateVehicleStatusIfStarted(Vehicle $vehicle, string $startDate): void
    {
        $isStartedTodayOrBefore = Carbon::parse($startDate)->startOfDay()->lte(today());

        if ($isStartedTodayOrBefore) {
            $vehicle->update(['status' => 'rented']);
        }
    }

    private function hasOverlap(int $vehicleId, string $start, string $end): bool
    {
        return RentalBooking::where('vehicle_id', $vehicleId)
            ->whereDoesntHave('vehicleReturn')
            ->where('rental_start_date', '<', $end)
            ->where('rental_end_date', '>', $start)
            ->exists();
    }
}
