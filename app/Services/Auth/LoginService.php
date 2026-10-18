<?php

namespace App\Services\Auth;

use App\Mail\Auth\LoginAlertMail;
use App\Models\User;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginService
{
    public function Login(array $request): array
    {
        if (
            !Auth::attempt([
                'email' => $request['email'],
                'password' => $request['password']
            ])
        ) {
            throw new AuthenticationException('Invalid email or password.');
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        Mail::to($user->email)->send(new LoginAlertMail(
            $user,
            now()->format('Y-m-d H:i:s'),
            request()->ip(),
            request()->userAgent()
        ));

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
