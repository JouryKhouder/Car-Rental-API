<?php

namespace Database\Seeders\DefaultData;
use App\Models\CarModel;
use App\Models\Make;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مصفوفة تشمل جميع الماركات الـ 10 المطلوبة مع أشهر موديلاتها الواقعية
        $carData = [
            'Toyota' => ['Camry', 'Corolla', 'Land Cruiser', 'Yaris'],
            'Mercedes-Benz' => ['C-Class', 'S-Class', 'G-Class', 'E-Class'],
            'BMW' => ['X5', 'M3', '3 Series', 'X6'],
            'Audi' => ['A3', 'A4', 'Q5', 'Q7'],
            'Ford' => ['Mustang', 'Explorer', 'F-150', 'Focus'],
            'Hyundai' => ['Elantra', 'Tucson', 'Sonata', 'Santa Fe'],
            'Kia' => ['Sportage', 'Cerato', 'Sorento', 'Rio'],
            'Nissan' => ['Altima', 'Patrol', 'Sunny', 'X-Trail'],
            'Chevrolet' => ['Camaro', 'Tahoe', 'Malibu', 'Silverado'],
            'Honda' => ['Civic', 'Accord', 'CR-V', 'Pilot'],
        ];

        foreach ($carData as $makeName => $models) {

            // 1. إنشاء الماركة
            $make = Make::create([
                'name' => $makeName
            ]);

            // 2. إنشاء الموديلات التابعة للماركة وربطها فوراً
            foreach ($models as $modelName) {
                CarModel::create([
                    'make_id' => $make->id,
                    'name' => $modelName
                ]);
            }
        }
    }
}
