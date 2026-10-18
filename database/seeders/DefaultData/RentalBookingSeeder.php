<?php

namespace Database\Seeders\DefaultData;

use App\Models\RentalBooking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RentalBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        RentalBooking::factory()->count(30)->create();
    }
}
