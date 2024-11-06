<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Motorcycle;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\CarFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Andrew',
            'email' => 'andrew@mail.com',
            'password' => Hash::make('password'),
        ]);
        $this->command->info('User andrew has been created!');

        Car::factory(5)->create();
        Motorcycle::factory(5)->create();
        $this->command->info('Dummy data has been created!');
    }
}
