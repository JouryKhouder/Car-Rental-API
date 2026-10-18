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
        // User::factory(10)->create();

        $this->call([
            // UserSeeder::class,
            // CustomerSeeder::class,
            MakeSeeder::class,
            CarCategorySeeder::class,
            FuelTypeSeeder::class,
            DriveTypeSeeder::class,
            VehicleSeeder::class,
            // RentalBookingSeeder::class,
            // DefaultData\VehicleReturnSeeder::class
            // VehicleLookupsSeeder::class,
        ]);
    }
}
