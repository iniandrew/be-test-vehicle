<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VehicleTransaction>
 */
class VehicleTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_id' => $this->faker->uuid,
            'quantity' => $this->faker->numberBetween(1, 10),
            'transaction_date' => $this->faker->dateTimeThisYear(),
        ];
    }
}
