<?php

namespace Database\Factories;

use App\Models\CarCategory;
use App\Models\DriveType;
use App\Models\FuelType;
use App\Models\Vehicle;
use App\Models\CarModel;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fuel_type_id' => FuelType::inRandomOrder()?->first()?->id ?? 1,
            'car_category_id' => CarCategory::inRandomOrder()?->first()?->id ?? 1,
            'model_id' => CarModel::inRandomOrder()?->first()?->id ?? 1,
            'drive_type_id' => DriveType::inRandomOrder()?->first()?->id ?? 1,
            'year' => 2023,
            'kilo_meterage' => rand(10000, 90000),
            'plate_number' => 'ABC-' . rand(100, 999) . '-' . rand(10, 99),
            'status' => 'available',
            'rental_price_per_day' => rand(30, 200),
            'color' => 'Black',
        ];
    }

}
