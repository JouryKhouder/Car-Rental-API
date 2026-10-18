<?php
namespace App\Services\Contracts;

use App\Models\User;

interface ChangePasswordInterface
{
    public function changePassword(User $user, array $request);
}
