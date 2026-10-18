<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreMakeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            // توحيد النص قبل الفحص لمنع تكرار marcedes و Marcedes
            $this->merge([
                'name' => ucwords(strtolower(trim($this->name))),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100', 'unique:makes,name'],
        ];
    }
}
