<?php
namespace App\Services\Admin;

use App\Models\User;
use Auth;

class CRUDAdminService
{
    public function show(User $admin)
    {
        return $admin;
    }

    public function update(User $admin, array $request)
    {
        $admin->update(
            $request
        );

        return $admin->fresh();
    }
    public function showAllCustomers()
    {

        return User::where('role', 'customer')->get();

    }

    public function showCustomer($customer_id)
    {
        return User::where('role', 'customer')->find($customer_id);
    }
}
