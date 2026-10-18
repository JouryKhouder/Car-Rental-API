<?php
namespace Database\Seeders\DefaultData;

use Illuminate\Database\Seeder;
use App\Models\FuelType; // تأكدي من مسار الموديل

class FuelTypeSeeder extends Seeder
{
    public function run(): void
    {
        // أنواع الوقود المعتمدة في السيارات
        $fuelTypes = [
            'Petrol (Gasoline)',
            'Diesel',
            'Electric (EV)',
            'Hybrid (HEV)',
            'Plug-in Hybrid (PHEV)'
        ];

        foreach ($fuelTypes as $fuel) {
            FuelType::create([
                'fuel_name' => $fuel
            ]);
        }
    }
}
