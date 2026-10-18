<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\RentalBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country' => fake()->country(),
            'city' => fake()->city(),
            'address' => fake()->address(),
            'driver_license_number' => fake()->unique()->bothify('??-########'),
            'national_id' => fake()->unique()->numerify('#####'),
        ];
    }

}
