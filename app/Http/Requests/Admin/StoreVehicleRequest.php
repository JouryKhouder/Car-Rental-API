<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'model_id'    => ['required', 'integer', 'exists:car_models,id'],
            'car_category_id' => ['required', 'integer', 'exists:car_categories,id'],
            'fuel_type_id'    => ['required', 'integer', 'exists:fuel_types,id'],
            'drive_type_id'   => ['required', 'integer', 'exists:drive_types,id'],
            'year'            => ['required', 'integer', 'digits:4', 'min:1990', 'max:' . (date('Y') + 1)],
            'plate_number'    => ['required', 'string', 'max:50', 'unique:vehicles,plate_number'],
            'color'           => ['required', 'string', 'max:50'],
            'kilo_meterage'   => ['required', 'integer', 'min:0'],
            'seats'           => ['required', 'integer', 'min:1', 'max:20'],
            'transmission'    => ['required', 'string', 'in:automatic,manual'],
            'rental_price_per_day'      => ['required', 'numeric', 'min:0'],
            'status'          => ['sometimes', 'string', 'in:available,rented,maintenance'],
        ];
    }
}
