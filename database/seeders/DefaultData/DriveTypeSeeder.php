<?php
namespace Database\Seeders\DefaultData;

use Illuminate\Database\Seeder;
use App\Models\DriveType; // تأكدي من مسار الموديل

class DriveTypeSeeder extends Seeder
{
    public function run(): void
    {
        // أنظمة الدفع الأساسية للسيارات
        $driveTypes = [
            'FWD (Front-Wheel Drive)',
            'RWD (Rear-Wheel Drive)',
            'AWD (All-Wheel Drive)',
            '4WD (Four-Wheel Drive)'
        ];

        foreach ($driveTypes as $type) {
            DriveType::create([
                'type_name' => $type // استخدمنا Type_Name بناءً على صورتك السابقة للمخطط
            ]);
        }
    }
}
