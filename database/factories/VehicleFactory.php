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
            'fuel_type_id' => FuelType::inRandomOrder()->first()->id ?? 1,
            'car_category_id' => CarCategory::inRandomOrder()->first()->id ?? 1,
            'model_id' => CarModel::inRandomOrder()->first()->id ?? 1,
            'drive_type_id' => DriveType::inRandomOrder()->first()->id ?? 1,
            'year' => $this->faker->year(),
            'kilo_meterage' => $this->faker->randomNumber(),
            'plate_number' => $this->faker->unique()->bothify('###-####'),
            'status' => $this->faker->randomElement(['available', 'rented', 'maintenance']),
            'rental_price_per_day' => $this->faker->randomFloat(2, 20, 500),
            'color' => $this->faker->colorName(),
        ];
    }

}
