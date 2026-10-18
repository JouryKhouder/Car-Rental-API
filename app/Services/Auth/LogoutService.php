<?php

namespace App\Services\Auth;

use App\Models\User;

class LogoutService
{
    public function Logout(User $user)
    {
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
    }
}
