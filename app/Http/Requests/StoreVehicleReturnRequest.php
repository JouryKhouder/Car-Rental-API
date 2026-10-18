<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleReturnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // الصلاحية محمية بميدلوير is_Admin على الراوت
    }

    public function rules(): array
    {
        return [
            // قراءة العداد الحالية للسيارة عند الإرجاع (مقارنتها مع عداد السيارة تتم بالسيرفس تحت القفل)
            'mileage'            => ['required', 'integer', 'min:0', 'max:9999999'],
            // غرامات / أضرار / تأخير
            'additional_charges' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'final_check_notes'  => ['nullable', 'string', 'max:1000'],
            // إذا السيارة بحاجة صيانة بعد الإرجاع بتصير maintenance بدل available
            'needs_maintenance'  => ['sometimes', 'boolean'],
        ];
    }
}