<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCarModelRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => ucwords(strtolower(trim($this->name))),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'make_id' => ['required', 'integer', 'exists:makes,id'],
            'name'    => [
                'required',
                'string',
                'max:100',
                // منع تكرار نفس اسم الموديل لنفس الشركة المصنعة
                Rule::unique('car_models', 'name')->where('make_id', $this->make_id),
            ],
        ];
    }
}
