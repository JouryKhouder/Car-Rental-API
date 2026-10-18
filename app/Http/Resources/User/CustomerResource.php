<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $user = $this->resource;
        $customer = $user?->customer ?? (isset($user->user) ? $user : null);
        $account = $customer?->user ?? $user;

        return [
            'id' => $account?->id,
            'first_name' => $account?->first_name,
            'last_name' => $account?->last_name,
            'full_name' => trim(($account?->first_name ?? '') . ' ' . ($account?->last_name ?? '')),
            'email' => $account?->email,
            'national_id' => $customer?->national_id,
            'driver_license_number' => $customer?->driver_license_number,
            'license_expiry_date' => $customer?->license_expiry_date,
            'address' => $customer?->address,
            'city' => $customer?->city,
            'country' => $customer?->country,
            'rental_count' => $customer?->rental_count,
            'created_at' => $account?->created_at?->format('Y-m-d H:i:s'),
        ];
    }
}
