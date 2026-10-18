<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;



class BookingCarRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $min = 1;
        $max = 60;


        return [
            'vehicle_id' => ['required', 'integer', 'exists:vehicles,id'],
            'rental_start_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'rental_end_date' => [
                'required',
                'date_format:Y-m-d',
                'after:rental_start_date',
                // تحديد مدة الاستعارة (من يوم إلى 60 يوم)
                function (string $attribute, mixed $value, \Closure $fail) use ($min, $max) {
                    try {
                        $days = Carbon::parse($this->input('rental_start_date'))
                            ->diffInDays(Carbon::parse($value));
                    } catch (\Throwable) {
                        return; // أخطاء الصيغة بتتعامل معها القواعد الثانية
                    }

                    if ($days < $min || $days > $max) {
                        $fail("مدة الاستعارة لازم تكون بين {$min} و {$max} يوم.");
                    }
                },
            ],
            'pickup_location' => ['required', 'string', 'max:255'],
            'dropoff_location' => ['required', 'string', 'max:255'],
            'initial_check_notes' => ['nullable', 'string', 'max:1000'],
            'paid_amount' => 'required|numeric|min:0',
        ];
    }
}

