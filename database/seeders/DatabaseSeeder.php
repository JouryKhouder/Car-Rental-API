<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\DefaultData\CarCategorySeeder;
use Database\Seeders\DefaultData\CustomerSeeder;
use Database\Seeders\DefaultData\DriveTypeSeeder;
use Database\Seeders\DefaultData\FuelTypeSeeder;
use Database\Seeders\DefaultData\MakeSeeder;
use Database\Seeders\DefaultData\RentalBookingSeeder;
use Database\Seeders\DefaultData\UserSeeder;
use Database\Seeders\DefaultData\VehicleSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $seeders = [
            UserSeeder::class,
            MakeSeeder::class,
            CarCategorySeeder::class,
            FuelTypeSeeder::class,
            DriveTypeSeeder::class,
            VehicleSeeder::class,
        ];

        foreach ($seeders as $seeder) {
            try {
                $this->call($seeder);
            } catch (\Throwable $e) {
                // إذا كان الجدول ممتلئاً مسبقاً (مثل MakeSeeder)، يتخطاه ويكمل للذي بعده!
                continue;
            }
        }
    }
}
