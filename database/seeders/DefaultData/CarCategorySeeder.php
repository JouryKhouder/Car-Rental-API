<?php
namespace Database\Seeders\DefaultData;

use Illuminate\Database\Seeder;
use App\Models\CarCategory; // تأكدي من مسار الموديل

class CarCategorySeeder extends Seeder
{
    public function run(): void
    {
        // فئات السيارات الأكثر شيوعاً عالمياً
        $categories = [
            'Sedan',
            'SUV',
            'Hatchback',
            'Coupe',
            'Convertible',
            'Minivan',
            'Pickup Truck',
            'Luxury'
        ];

        foreach ($categories as $category) {
            CarCategory::create([
                'catygory_name' => $category
            ]);
        }
    }
}
