<?php
namespace Database\Factories;

use App\Models\RentalBooking;
use App\Models\RentalTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Vehicle;
use App\Models\Customer;
use Carbon\Carbon;

class RentalBookingFactory extends Factory
{
    public function configure(): static
    {
        return $this->afterCreating(function (RentalBooking $booking) {//  هون بيتم تنفيذ هذه الدالة بعد تنفيد دالة الديفينشن و بعدها بقوم بإرجاع هي البيانات بعد الانتهاء من تنفيذها
        // عم قلو روح عالموديل اللي أنشأتو بالبيانات الافتراضية و بناء عليه عملي هي التعليمات
            // 1. تغيير حالة السيارة إلى مؤجرة
            $booking->vehicle()->update(['status' => 'rented']);

            // 3. إنشاء معاملة الدفعة الأولى بنسبة 30%
            RentalTransaction::create([
                'booking_id' => $booking->id, // هنا نستخدم id الحجز الذي تم إنشاؤه للتو
                'payment_method' => fake()->randomElement([
                    'sham cash',
                    'visa card',
                    'cash'
                ]),// بس توصل لهون حاكيني لانو عم فكر نخلي طرق الدفع اينوم
                'payment_type'  => 'deposit',// روح عدل المايجريشن و سميها ديبوست الدفعة الأولى
                'amount'            => $booking->initial_total_due_amount * 0.30,
                'transaction_date'  => $booking->rental_start_date, // تاريخ الدفعة هو نفسه تاريخ بداية الحجز
            ]);
        });
    }

    public function definition(): array
    {
        // 1. توليد تاريخ بداية عشوائي
        $startDate = fake()->dateTimeBetween('-1 month', 'now');

        // 2. توليد تاريخ نهاية عشوائي
        $endDate = fake()->dateTimeBetween($startDate, Carbon::parse($startDate)->addDays(rand(1, 14)));

        return [
            'vehicle_id' => Vehicle::where('status', 'available')->inRandomOrder()->first()->id
                ?? Vehicle::factory()->create(['status' => 'available'])->id,

            'customer_id' => Customer::inRandomOrder()->first()->id ?? Customer::factory(),

            'rental_start_date' => $startDate,
            'rental_end_date' => $endDate,

            'pickup_location' => fake()->city(),
            'dropoff_location' => fake()->city(),

            // سحب السعر الفعلي من السيارة المختارة
            // يعني عم عرف فانكشن هاد الفانكشن بياخد البيانات اللي فوق الوهمية اللي عملناها مشان يوصل عن طريقها للسيارة
            // و يوصل عن طريق السيارة لسعر تأجيرها باليوم الواحد
            'rental_price_per_day' => function (array $attributes) {
                return Vehicle::find($attributes['vehicle_id'])->rental_price_per_day;
            },

            'initial_check_notes' => fake()->optional()->sentence(),
        ];
    }
}
