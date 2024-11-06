<?php

namespace Database\Factories;

use App\Models\Car;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    protected $model = Car::class;
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
            'engine' => $this->faker->randomElement(['Listrik', 'Bensin', 'Diesel']),
            'passenger_capacity' => $this->faker->randomNumber(1),
            'car_type' => $this->faker->randomElement(['SUV', 'Sedan', 'LCGC']),
        ];
    }
}
