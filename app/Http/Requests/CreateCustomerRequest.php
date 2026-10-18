<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {return [
        // حقول جدول الـ Users
        'first_name'            => ['required', 'string', 'max:55'],
        'last_name'             => ['required', 'string', 'max:55'],
        'email'                 => ['required', 'email', 'unique:users,email'],
        'password'              => ['required', 'string', 'min:8', 'confirmed'],

        // حقول جدول الـ Customers (أصبحت إجبارية بالكامل بناءً على رغبتك)
        'country'               => ['required', 'string', 'max:100'],
        'city'                  => ['required', 'string', 'max:100'],
        'address'               => ['required', 'string', 'max:255'],
        'driver_license_number' => ['required', 'string', 'max:50', 'unique:customers,driver_license_number'],
        'national_id'           => ['required', 'string', 'max:50', 'unique:customers,national_id'],
    ];
    }
}
