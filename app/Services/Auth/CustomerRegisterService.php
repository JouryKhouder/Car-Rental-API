<?php
namespace App\Services\Auth;

use App\Mail\Auth\WelcomeMail;
use App\Services\Contracts\RegisterInterface;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CustomerRegisterService implements RegisterInterface
{
    public function register(array $data): array
    {
        // نستخدم Transaction لضمان أنه إذا فشل جدول لا يتم إنشاء الجدول الآخر
        return DB::transaction(function () use ($data) {

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name'  => $data['last_name'],
                'email'      => $data['email'],
                'password'   => Hash::make($data['password']),
                'role'       => 'customer',
            ]);

            $customer = Customer::create([
                'user_id'              => $user->user_id ?? $user->id,
                'country'              => $data['country'] ?? null,
                'city'                 => $data['city'] ?? null,
                'address'              => $data['address'] ?? null,
                'driver_license_number'=> $data['driver_license_number'] ?? null,
                'national_id'          => $data['national_id'] ?? null,
                'rental_count'         => 0,
            ]);

            $token = $user->createToken('customer_auth_token')->plainTextToken;

            Mail::to($user->email)->send(new WelcomeMail($user));

            return [
                'user'     => $user,
                'customer' => $customer,
                'token'    => $token,
            ];
        });
    }
}
