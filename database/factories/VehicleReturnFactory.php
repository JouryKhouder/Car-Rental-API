<?php

namespace Database\Factories;

use App\Models\RentalBooking;
use App\Models\RentalTransaction;
use App\Models\Vehicle;
use App\Models\VehicleReturn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VehicleReturn>
 */
class VehicleReturnFactory extends Factory
{

    public function configure(): static
    {
        return $this->afterCreating(function (VehicleReturn $returnRecord) {
            $booking = RentalBooking::find($returnRecord->rental_booking_id);

            // 1. نجلب قيمة العربون الذي دفعه الزبون وقت الحجز (والذي يُفترض أنه 30%)
            $depositAmount = $booking->initial_total_due_amount * 0.30;// يعني قديش دفع الزبون دفعة أولى

            // 2. المعادلة المحاسبية الصحيحة: الرصيد المتبقي = (إجمالي التكلفة الفعلية - ما دفعه مسبقاً)
            // (المبلغ الكلي - الدفعة الأولى) لنشوف قديش بقيان عليه يدفع مصاري
            $remainingBalance = $returnRecord->actual_total_due_amount - $depositAmount;


            // 3. إنشاء المعاملة المالية المناسبة
            if ($remainingBalance > 0) {
                // الزبون لا يزال مديناً لنا (هذا سيشمل الإرجاع على الموعد، والتأخير، وحتى الإرجاع المبكر الذي لم يغطيه العربون)
                RentalTransaction::create([
                    'booking_id' => $booking->id, // تم تعديل الاسم ليطابق صورتك
                    'payment_method' => fake()->randomElement(['cash', 'visa card', 'sham cash']), // إضافة طريقة الدفع من صورتك
                    'payment_type' => 'final_payment',
                    'amount' => $remainingBalance,
                    'transaction_date' => $returnRecord->actual_return_date,
                ]);
            } elseif ($remainingBalance < 0) {
                // حالة نادرة: الزبون دفع عربوناً يغطي أكثر من استخدام السيارة (إرجاع مبكر جداً)
                RentalTransaction::create([
                    'booking_id' => $booking->id,
                    'payment_method' => 'cash', // المرتجعات غالباً كاش
                    'payment_type' => 'refund',
                    'amount' => abs($remainingBalance), // تحويل الرقم السالب لموجب
                    'transaction_date' => $returnRecord->actual_return_date,
                ]);
            }

            // 4. إعادة حالة السيارة إلى متاحة
            $booking->vehicle()->update(['status' => 'available']);
        });
    }

    public function definition(): array
    {
        return [
            // كيف يعمل هذا السطر؟
            //VehicleReturn::pluck('rental_booking_id'): يذهب هذا الجزء إلى جدول الإعادات (vehicle_returns) ويجمع كل أرقام الحجوزات (ids) التي تمت إعادتها وتمت محاسبتها مسبقاً.
            //whereNotIn('id', ...): يخبر لارافيل: "ابحث لي في جدول الحجوزات، واستبعد كل الأرقام التي جلبناها في الخطوة الأولى".
            //inRandomOrder()->first(): الآن سيختار حجزاً عشوائياً من الحجوزات "النشطة فقط" (التي لم ترجع سيارتها بعد).

            'rental_booking_id' => RentalBooking::whereNotIn('id', VehicleReturn::pluck('rental_booking_id'))
                ->inRandomOrder()
                ->first()->id,

            // 1. توليد تاريخ الإرجاع الفعلي
            'actual_return_date' => function (array $attributes) {
                $booking = RentalBooking::find($attributes['rental_booking_id']);
                $start_date = Carbon::parse($booking->rental_start_date);
                $initial_end_date = Carbon::parse($booking->rental_end_date);

                $expectedDays = $start_date->diffInDays($initial_end_date) ?: 1;// عدد الأيام اللي لازم كان يرجعهم فيه

                // توليد أيام عشوائية: من 1 (إرجاع مبكر جداً) إلى متأخر بيومين (+2)
                $actualDays = rand(1, $expectedDays + 2);
                //هذا السطر هو الذي يصنع السيناريوهات الوهمية لاختباراتك! دالة rand تختار رقماً عشوائياً بين 1 و (عدد الأيام المتوقعة + 2).
                //سيناريو الإرجاع المبكر (مرتجع مالي): إذا كان العقد 5 أيام، واختار الرقم العشوائي 2.
                //سيناريو الإرجاع على الموعد: إذا اختار الرقم العشوائي 5.
                //سيناريو التأخير (غرامة أو دفعة إضافية): إذا اختار الرقم العشوائي 6 أو 7 (لأننا أضفنا +2 كحد أقصى للتأخير).

                // إضافة الأيام العشوائية لتاريخ بداية الحجز لمعرفة متى أرجعها بالضبط
                return $start_date->copy()->addDays($actualDays);
            },

            'actual_rental_days' => function (array $attributes) {
                $booking = RentalBooking::find($attributes['rental_booking_id']);
                $start_date = Carbon::parse($booking->rental_start_date);
                // تعليمة كاربون تقوم بتحويل التاريخ الموجود في الداتا بيز من نص الى تاريخ فعلي
                $actual_return_date = Carbon::parse($attributes['actual_return_date']);
                // نجلب يوم الاعادة الفعلي من المعلومات التي في الاعلى عن طريق المصفوفة التي سميناها (اتربيوت)

                $days = $start_date->diffInDays($actual_return_date);

                return $days === 0 ? 1 : $days;
                // نحسب الفرق بين يوم الاعادة الفعلي و بين تاريخ اليوم الذي استلم فيه السيارة
                // اذا تمت الاعادة في نفس اليوم الدالة تحسبها ك 0 لذلك اذا كانت 0 ف بهذه الحالة يعتبر تمت استعارتها ليوم واحد فقط
            },

            // تابع لاعادة المسافة الجديدة بعد اعادة السيارة
            'mileage' => function (array $attributes) {
                $booking = RentalBooking::find($attributes['rental_booking_id']);
                $vehicle = Vehicle::find($booking->vehicle_id);
                $old_mileage = $vehicle->kilo_meterage;

                $new_mileage = rand(50, 1000) + $old_mileage;

                return $new_mileage;
            },

            'consumed_mileage' => function (array $attributes) {
                $booking = RentalBooking::find($attributes['rental_booking_id']);
                $new_mileage = $attributes['mileage'];
                $vehicle = Vehicle::find($booking->vehicle_id);
                $old_mileage = $vehicle->kilo_meterage;

                $consumed_mileage = $new_mileage - $old_mileage;

                return $consumed_mileage;
            },

            // 1. تقييم حالة السيارة عند الإرجاع
            'final_check_notes' => function () {
                $damages = [
                    'كسر في المرآة الجانبية الأيسر',
                    'خدش عميق في الباب الأمامي',
                    'تلف في المقاعد الداخلية',
                    'كسر في الضوء الخلفي'
                ];

                $hasdamage = fake()->boolean(20);

                return $hasdamage ? fake()->randomElement($damages) : 'السيارة سليمة و لا يوجد أضرار';
            },

            // 2. حساب الرسوم الإضافية (للأضرار)
            'additional_charges' => function (array $attributes) {

                if ($attributes['final_check_notes'] === 'السيارة سليمة و لا يوجد أضرار')
                    return 0;

                return rand(50, 400);
            },

            // 3. حساب الفاتورة الفعلية النهائية بالكامل
            'actual_total_due_amount' => function (array $attributes) {
                $booking = RentalBooking::find($attributes['rental_booking_id']);

                // الإيجار الأساسي = عدد الأيام الفعلية × السعر اليومي
                $baseRentCost = $booking->rental_price_per_day * $attributes['actual_rental_days'];

                // الفاتورة النهائية = الإيجار الأساسي + أي رسوم أضرار ظهرت في السطر السابق
                return $baseRentCost + $attributes['additional_charges'];
            },
        ];
    }
}
