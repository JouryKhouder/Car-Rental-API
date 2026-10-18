<?php
namespace Database\Seeders\DefaultData;

use Illuminate\Database\Seeder;
use App\Models\RentalBooking;
use App\Models\VehicleReturn;

class VehicleReturnSeeder extends Seeder
{
    public function run(): void
    {
        // 1. جلب عدد محدد من الحجوزات النشطة (التي ليس لها سجل إعادة مسبق)
        $activeBookings = RentalBooking::whereNotIn('id', VehicleReturn::pluck('rental_booking_id'))
            ->inRandomOrder()
            ->take(15) // يمكنك تغيير الرقم حسب حجم الداتا المطلوبة
            ->get();

        // 2. التحقق من وجود حجوزات لمنع الأخطاء إذا كانت الداتابيز فارغة
        if ($activeBookings->isEmpty()) {
            return;
        }

        // 3. بناء سجل الإعادة لكل حجز بشكل منفرد
        foreach ($activeBookings as $booking) {
            VehicleReturn::factory()->create([
                // هنا نجبر الفاكتوري على استخدام هذا الحجز تحديداً بدلاً من اختيار واحد عشوائي
                'rental_booking_id' => $booking->id
            ]);
        }
    }
}
