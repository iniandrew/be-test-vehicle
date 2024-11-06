<?php

namespace Database\Factories;

use App\Enums\VehicleType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Motorcycle>
 */
class MotorcycleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'year_of_manufacture' => (int) $this->faker->year,
            'color' => $this->faker->colorName,
            'price' => $this->faker->randomNumber(5),
            'stock' => $this->faker->randomNumber(2),
            'engine' => $this->faker->randomElement(['Listrik', 'Bensin']),
            'suspension_type' => $this->faker->randomElement(['Parallel Fork', 'Telescopic Fork', 'Telescopic Up Side Down']),
            'transmission_type' => $this->faker->randomElement(['Manual', 'Matic']),
        ];
    }
}
