<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // فلاتر الجداول المرتبطة (Foreign Keys)
            'make_id'         => ['nullable', 'integer', 'exists:makes,id'],
            'model_id'        => ['nullable', 'integer', 'exists:car_models,id'],
            'car_category_id' => ['nullable', 'integer', 'exists:car_categories,id'],
            'fuel_type_id'    => ['nullable', 'integer', 'exists:fuel_types,id'],
            'drive_type_id'   => ['nullable', 'integer', 'exists:drive_types,id'],

            // فلاتر خصائص السيارة المباشرة
            'year'            => ['nullable', 'integer', 'digits:4'],
            'color'           => ['nullable', 'string', 'max:50'],
            'status'          => ['nullable', 'string', 'in:available,rented,maintenance'],

            // فلاتر النطاق (تُقارن مع Rental_Price_Per_Day و kilo_meterage)
            'min_price'       => ['nullable', 'numeric', 'min:0'],
            'max_price'       => ['nullable', 'numeric', 'gte:min_price'],
            'max_mileage'     => ['nullable', 'numeric', 'min:0'], // الحد الأقصى للكيلومترات المقطوعة

            // البحث العام (برقم اللوحة أو اللون أو السنة)
            'search'          => ['nullable', 'string', 'max:100'],
        ];
    }
}
