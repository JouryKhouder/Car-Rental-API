<?php

namespace App\Services\Vehicle;

use App\Exceptions\BookingException;
use App\Mail\Payment\PaymentReceiptMail;
use App\Mail\Rental\VehicleReturnConfirmationMail;
use App\Models\RentalBooking;
use App\Models\RentalTransaction;
use App\Models\Vehicle;
use App\Models\VehicleReturn;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class VehicleReturnService
{
    public function returnVehicle(int $bookingId, array $data): VehicleReturn
    {
        $vehicleId = RentalBooking::findOrFail($bookingId)->vehicle_id;

        return DB::transaction(function () use ($bookingId, $vehicleId, $data) {

            // جلب البيانات مع القفل لتجنب الـ Deadlock
            $vehicle = $this->lockAndGetVehicle($vehicleId);
            $booking = $this->lockAndGetBooking($bookingId);

            $this->validateReturnConditions($vehicle, $booking, $data);

            $calculatedData = $this->calculateReturnMetrics($vehicle, $booking, $data);

            $returnRecord = $this->createVehicleReturnRecord($booking->id, $calculatedData, $data);

            $paidAmount = RentalTransaction::where('booking_id', $booking->id)
                ->where('payment_type', 'deposit')
                ->sum('amount');

            $remainingBalance = max(0, $calculatedData['total'] - $paidAmount);

            $payment = null;
            if ($remainingBalance > 0) {
                $payment = $this->processPayment($booking->id, $remainingBalance);
            }
            $this->updateVehicleState($vehicle, $booking, $calculatedData['mileage'], $data);

            Mail::to($booking->customer->user->email)->send(new VehicleReturnConfirmationMail(
                $booking->customer->user,
                $booking,
                $vehicle,
            ));
            if ($payment) {
                Mail::to($booking->customer->user->email)->send(new PaymentReceiptMail(
                    $booking->customer->user,
                    $payment,
                    $booking,
                    $vehicle,
                    $remainingBalance
                ));
            }
            return $returnRecord->load(['rentalBooking.vehicle.carModel.make']);
        });
    }


    // جلب السيارة مع قفل للتعديل
    private function lockAndGetVehicle(int $vehicleId): Vehicle
    {
        return Vehicle::lockForUpdate()->findOrFail($vehicleId);
    }


    // جلب الحجز مع قفل للتعديل
    private function lockAndGetBooking(int $bookingId): RentalBooking
    {
        return RentalBooking::lockForUpdate()->findOrFail($bookingId);
    }

    private function validateReturnConditions(Vehicle $vehicle, RentalBooking $booking, array $data): void
    {
        // أ) ممنوع إرجاع حجز مُرجَع مسبقاً
        if (VehicleReturn::where('rental_booking_id', $booking->id)->exists()) {
            throw new BookingException('تم إرجاع هذه السيارة مسبقاً لهذا الحجز.', 409);
        }

        // ب) ممنوع إرجاع حجز لم يبدأ بعد
        $today = today();
        $bookingStartDate = Carbon::parse($booking->rental_start_date);
        if ($bookingStartDate->gt($today)) {
            throw new BookingException('لا يمكن إرجاع سيارة لحجز لم يبدأ بعد (تاريخ البداية ' . $bookingStartDate->format('Y-m-d') . ').', 409);
        }

        // ج) العداد لا يمكن أن يكون أقل من عداد السيارة المسجل
        $mileage = (int) $data['mileage'];
        if ($mileage < $vehicle->kilo_meterage) {
            throw new BookingException("قراءة العداد ({$mileage}) أقل من آخر قراءة مسجلة للسيارة ({$vehicle->kilo_meterage}).", 422);
        }
    }

    private function calculateReturnMetrics(Vehicle $vehicle, RentalBooking $booking, array $data): array
    {
        $today = today();
        $bookingStartDate = Carbon::parse($booking->rental_start_date);

        $mileage = (int) $data['mileage'];
        $consumedMileage = $mileage - $vehicle->kilo_meterage;

        $startDate = $bookingStartDate->copy()->startOfDay();
        $actualDays = max(1, (int) $startDate->diffInDays($today->copy()->startOfDay()));

        $charges = round((float) ($data['additional_charges'] ?? 0), 2);
        $totalDueAmount = round($actualDays * (float) $booking->rental_price_per_day + $charges, 2);

        return [
            'today' => $today->toDateString(),
            'actual_days' => $actualDays,
            'mileage' => $mileage,
            'consumed_mileage' => $consumedMileage,
            'charges' => $charges,
            'total' => $totalDueAmount,
        ];
    }

    private function createVehicleReturnRecord(int $bookingId, array $metrics, array $data): VehicleReturn
    {
        $vehicleReturn = new VehicleReturn();
        $vehicleReturn->forceFill([
            'rental_booking_id' => $bookingId,
            'actual_return_date' => $metrics['today'],
            'actual_rental_days' => $metrics['actual_days'],
            'mileage' => $metrics['mileage'],
            'consumed_mileage' => $metrics['consumed_mileage'],
            'final_check_notes' => $data['final_check_notes'] ?? null,
            'additional_charges' => $metrics['charges'],
            'actual_total_due_amount' => $metrics['total'],
        ])->save();

        return $vehicleReturn;
    }

    private function processPayment(int $bookingId, float $totalAmount): RentalTransaction
    {
        return RentalTransaction::create([
            'booking_id' => $bookingId,
            'payment_method' => 'cash',
            'payment_type' => 'final_payment',
            'amount' => $totalAmount,
            'transaction_date' => now(),
        ]);
    }

    private function updateVehicleState(Vehicle $vehicle, RentalBooking $booking, int $newMileage, array $data): void
    {
        $vehicle->update([
            'kilo_meterage' => $newMileage,
            'status' => $this->resolveVehicleStatus($vehicle, $booking, $data),
        ]);
    }

    private function resolveVehicleStatus(Vehicle $vehicle, RentalBooking $returned, array $data): string
    {
        if (!empty($data['needs_maintenance'])) {
            return 'maintenance';
        }

        $hasOtherActive = RentalBooking::where('vehicle_id', $vehicle->id)
            ->where('id', '!=', $returned->id)
            ->whereDoesntHave('vehicleReturn')
            ->where('rental_start_date', '<=', today())
            ->where('rental_end_date', '>', today())
            ->exists();

        return $hasOtherActive ? 'rented' : 'available';
    }
}
