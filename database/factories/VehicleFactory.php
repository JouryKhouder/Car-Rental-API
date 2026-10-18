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
            'fuel_type_id' => FuelType::inRandomOrder()->first()->id,
            'car_category_id' => CarCategory::inRandomOrder()->first()->id,
            'model_id' => CarModel::inRandomOrder()->first()->id,
            'drive_type_id' => DriveType::inRandomOrder()->first()->id,
            'year' => fake()->year(),
            'kilo_meterage' => fake()->randomNumber(),
            'plate_number' => fake()->unique()->bothify('###-####'),
            'status' => fake()->randomElement(['available', 'rented', 'maintenance']),
            'rental_price_per_day' => fake()->randomFloat(2, 20, 500),
            // النتيجة ستكون مشابهة لـ: 45.50 أو 120.99
            'color'=>fake()->colorName(),
        ];
    }
}
