<?php

namespace App\Services\Auth;

use App\Mail\Auth\WelcomeMail;
use App\Models\User;
use App\Services\Contracts\RegisterInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminRegisterService implements RegisterInterface
{
    public function Register(array $request): array
    {
        $user = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => 'admin'
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        Mail::to($user->email)->send(new WelcomeMail($user));

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
