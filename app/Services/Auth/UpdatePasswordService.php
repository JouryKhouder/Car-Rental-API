<?php
namespace App\Services\Auth;

use App\Mail\Auth\PasswordChangedMail;
use App\Models\User;
use App\Services\Contracts\ChangePasswordInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;


class UpdatePasswordService implements ChangePasswordInterface
{
    public function changePassword(User $user, array $data)
    {
        if (!Hash::check($data['old_password'], $user->password)) {
            throw ValidationException::withMessages([
                'message' => 'كلمة المرور الحالية غير صحيحة.'
            ]);
        }

        $user->update([
            'password' => Hash::make($data['new_password'])
        ]);

        Mail::to($user->email)->send(new PasswordChangedMail($user));

        // (ميزة اختيارية) إلغاء كل التوكنات السابقة من الأجهزة الأخرى
        $user->tokens()->where('id', '!=', $user->currentAccessToken()->id)->delete();
    }
}
