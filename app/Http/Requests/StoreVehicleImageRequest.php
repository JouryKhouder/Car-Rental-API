<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'images'   => 'required|array',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'images.required' => 'يرجى إرفاق صور للسيارة.',
            'images.array' => 'يجب أن تكون الصور على شكل مصفوفة.',
            'images.*.image' => 'الملف المرفق يجب أن يكون صورة.',
            'images.*.mimes' => 'الصيغ المدعومة هي: jpeg, png, jpg, webp.',
            'images.*.max' => 'حجم الصورة يجب ألا يتجاوز 2 ميغابايت.',
        ];
    }
}
