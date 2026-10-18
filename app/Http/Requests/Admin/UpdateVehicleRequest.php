<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // جلب معرف السيارة من الرابط لاستثنائه من فحص التكرار لرقم اللوحة
        $vehicleId = $this->route('vehicle');

        return [
            'car_model_id'    => ['sometimes', 'integer', 'exists:car_models,id'],
            'car_category_id' => ['sometimes', 'integer', 'exists:car_categories,id'],
            'fuel_type_id'    => ['sometimes', 'integer', 'exists:fuel_types,id'],
            'drive_type_id'   => ['sometimes', 'integer', 'exists:drive_types,id'],
            'year'            => ['sometimes', 'integer', 'digits:4', 'min:1990', 'max:' . (date('Y') + 1)],
            'plate_number'    => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('vehicles', 'plate_number')->ignore($vehicleId)
            ],
            'color'           => ['sometimes', 'string', 'max:50'],
            'kilo_meterage'   => ['sometimes', 'integer', 'min:0'],
            'seats'           => ['sometimes', 'integer', 'min:1', 'max:20'],
            'transmission'    => ['sometimes', 'string', 'in:automatic,manual'],
            'daily_rate'      => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}
