<?php
   namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // بيانات جدول Users
            'first_name'            => ['sometimes', 'string', 'max:255'],
            'last_name'             => ['sometimes', 'string', 'max:255'],
            'email'                 => ['sometimes', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($this->user()?->id)],

            // بيانات جدول Customers
            'country'               => ['sometimes', 'string', 'max:255'],
            'city'                  => ['sometimes', 'string', 'max:255'],
            'address'               => ['sometimes', 'string', 'max:500'],
            'driver_license_number' => ['sometimes', 'string', \Illuminate\Validation\Rule::unique('customers', 'driver_license_number')->ignore($this->user()?->customer?->id)],
            'national_id'           => ['sometimes', 'string', \Illuminate\Validation\Rule::unique('customers', 'national_id')->ignore($this->user()?->customer?->id)],
        ];
    }
}
