<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Exceptions\BookingException;
use App\Models\RentalBooking;

class CRUDCustomerService
{
    public function updateCustomer(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            $customer = Customer::where('user_id', $userId)->firstOrFail();

            $userData = array_filter([
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'] ?? null,
            ], fn($value) => !is_null($value));

            if (!empty($userData)) {
                User::where('id', $userId)->update($userData);
            }

            $customerData = array_filter([
                'country' => $data['country'] ?? null,
                'city' => $data['city'] ?? null,
                'address' => $data['address'] ?? null,
                'driver_license_number' => $data['driver_license_number'] ?? null,
                'national_id' => $data['national_id'] ?? null,
            ], fn($value) => !is_null($value));

            if (!empty($customerData)) {
                $customer->update($customerData);
            }

            return $customer->fresh('user');
        });
    }

    public function deleteCustomerWithUser(int $userId): bool
    {

        $customer = Customer::where('user_id', $userId)->first();
        if ($customer && $customer->rentalBookings()->exists()) {
            throw new BookingException('لا يمكن حذف الزبون لأنه مرتبط بحجوزات قائمة.', 409);
        }

        return DB::transaction(function () use ($userId) {
            Customer::where('user_id', $userId)->delete();

            $userDeleted = User::where('id', $userId)->delete();

            return $userDeleted > 0;
        });

    }

    public function showCustomerInfo(int $userId)
    {
        $user = User::where('id', $userId)->with('customer')->firstOrFail();
        return ['user' => $user];

    }

}
