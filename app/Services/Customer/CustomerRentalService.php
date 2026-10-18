<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\RentalBooking;

class CustomerRentalService
{
    public function getCustomerHistory($userId)
    {
        $customer = Customer::where('user_id', $userId)->first();

        if (!$customer) {
            return [];
        }

        $rentals = RentalBooking::with('vehicle.carModel.make')
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return $rentals->map(function ($rental) {

            $isReturned = !is_null($rental->actual_return_date);

            return [
                'rental_id' => $rental->id,

                'vehicle' => $rental->vehicle->carModel->make->name . ' ' . $rental->vehicle->carModel->name . ' (' . $rental->vehicle->year . ')',

                'plate_number' => $rental->vehicle->plate_number,
                'start_date' => $rental->rental_start_date,
                'expected_end_date' => $rental->rental_end_date,
                'actual_return_date' => $isReturned ? $rental->actual_return_date : 'لم يتم الإرجاع بعد',
                'status' => $isReturned ? 'مكتمل (تم الإرجاع)' : 'نشط (لسا أكتف)',
                'initial_cost' => $rental->initial_total_due_amount,
            ];
        });
    }
    public function getReceiptDetails($userId, $rentalId)
    {
        $customer = Customer::where('user_id', $userId)->first();

        if (!$customer) {
            return null;
        }

        $rental = RentalBooking::with('vehicle.carModel.make')
            ->where('customer_id', $customer->id)
            ->where('id', $rentalId)
            ->first();

        if (!$rental) {
            return null;
        }

        $isReturned = !is_null($rental->actual_return_date);

        return [
            'receipt_number' => 'REC-00' . $rental->id,
            'status' => $isReturned ? 'مكتمل' : 'نشط (السيارة بحوزتك)',
            'dates' => [
                'rented_on' => $rental->rental_start_date,
                'expected_return' => $rental->rental_end_date,
                'returned_on' => $isReturned ? $rental->actual_return_date : '---',
            ],
            'vehicle_details' => [
                'name' => $rental->vehicle->carModel->make->name . ' ' . $rental->vehicle->carModel->name,
                'plate' => $rental->vehicle->plate_number,
                'color' => $rental->vehicle->color,
            ],
            'financials' => [
                'rental_price_per_day' => $rental->vehicle->rental_price_per_day,
                'total_base_cost' => $rental->initial_total_due_amount,
            ]
        ];
    }
}
