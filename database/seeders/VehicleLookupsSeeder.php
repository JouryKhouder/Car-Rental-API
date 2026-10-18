<?php

namespace Database\Seeders;

use App\Models\CarCategory;
use App\Models\DriveType;
use App\Models\FuelType;
use Illuminate\Database\Seeder;

class VehicleLookupsSeeder extends Seeder
{
    public function run(): void
    {
        // 1. أنواع الوقود (Fuel Types)
        $fuelTypes = [
            'Petrol',       // بنزين
            'Diesel',       // ديزل / مازوت
            'Electric',     // كهرباء
            'Hybrid',       // هايبرد (هجين)
            'Plug-in Hybrid'
        ];

        foreach ($fuelTypes as $type) {
            FuelType::firstOrCreate(['fuel_name' => $type]);
        }

        // 2. أنواع الدفع (Drive Types)
        $driveTypes = [
            'FWD', // دفع أمامي (Front-Wheel Drive)
            'RWD', // دفع خلفي (Rear-Wheel Drive)
            'AWD', // دفع كلي مستمر (All-Wheel Drive)
            '4WD', // دفع رباعي (Four-Wheel Drive)
        ];

        foreach ($driveTypes as $drive) {
            DriveType::firstOrCreate(['type_name' => $drive]);
        }

        // 3. فئات السيارات (Car Categories)
        $categories = [
            'Sedan',       // سيدان
            'SUV',         // جيب / عائلية مرتفعة
            'Hatchback',   // هاتشباك
            'Coupe',       // كوبيه (بابين)
            'Convertible', // كشف
            'Van',         // فان عائلي
            'Pickup',      // بيك أب
            'Luxury',      // فارهة
        ];

        foreach ($categories as $category) {
            CarCategory::firstOrCreate(['catygory_name' => $category]);
        }
    }
}
