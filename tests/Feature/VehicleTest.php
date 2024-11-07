<?php

use App\Models\Car;
use App\Models\User;
use App\Models\VehicleTransaction;
use Illuminate\Http\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->token = JWTAuth::fromUser($this->user);
});

it('can create a vehicle', function () {
    $payload = [
        'type' => 'car',
        'year_of_manufacture' => 2024,
        'color' => 'Red',
        'price' => 10000000,
        'stock' => 100,
        'engine' => 'V12',
        'passenger_capacity' => 4,
        'car_type' => 'Sedan',
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->postJson('/api/vehicles/', $payload);

    expect($response->status())->toBe(Response::HTTP_CREATED)
        ->and($response->json('status'))->toBe('success');
});

it('can get all vehicles', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->getJson('/api/vehicles/');

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json('status'))->toBe('success');
});

it('can update a vehicle', function () {
    $vehicle = Car::factory()->create();

    $payload = [
        'type' => 'car',
        'year_of_manufacture' => 2024,
        'color' => 'Blue',
        'price' => 10000000,
        'stock' => 50,
        'engine' => 'V12',
        'passenger_capacity' => 4,
        'car_type' => 'Sedan',
    ];


    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->putJson('/api/vehicles/' . $vehicle->id, $payload);

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json('status'))->toBe('success')
        ->and($response->json('data.color'))->toBe('Blue')
        ->and($response->json('data.stock'))->toBe(50);
});

it('can delete a vehicle', function () {
    $vehicle = Car::factory()->create();

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->deleteJson('/api/vehicles/' . $vehicle->id);

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json('status'))->toBe('success');
});

it('can get a vehicle stock', function () {
    $vehicle = Car::factory()->create();

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->getJson('/api/vehicles/' . $vehicle->id . '/stock');

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json('status'))->toBe('success')
        ->and($response->json('data.stock'))->toBe($vehicle->stock);
});

it('can sell a vehicle', function () {
    $vehicle = Car::factory()->create();

    $payload = [
        'quantity' => 1,
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->postJson('/api/vehicles/' . $vehicle->id . '/sell', $payload);

    expect($response->status())->toBe(Response::HTTP_CREATED)
        ->and($response->json('status'))->toBe('success')
        ->and($response->json('data.vehicle_id'))->toBe($vehicle->id);
});

it('can not sell a vehicle with insufficient stock', function () {
    $vehicle = Car::factory()->create();

    $payload = [
        'quantity' => $vehicle->stock + 1,
    ];

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->postJson('/api/vehicles/' . $vehicle->id . '/sell', $payload);

    expect($response->status())->toBe(Response::HTTP_BAD_REQUEST)
        ->and($response->json('status'))->toBe('error')
        ->and($response->json('message'))->toBe('Not enough stock');
});

it('can get sales report by a specific vehicle', function () {

    $vehicle = Car::factory()->create();
    $transactions = VehicleTransaction::factory()->count(2)->create([
        'vehicle_id' => $vehicle->id,
        'quantity' => 4,
        'transaction_date' => now(),
    ]);
    $expectedTotalSales = $transactions->sum('quantity');
    $expectedTotalRevenue = $transactions->sum(fn ($transaction) => $transaction->quantity * $vehicle->price);

    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->getJson('/api/vehicles/' . $vehicle->id . '/sales-report');

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json('status'))->toBe('success')
        ->and($response->json('data.total_sales'))->toBe($expectedTotalSales)
        ->and($response->json('data.total_revenue'))->toBe($expectedTotalRevenue);
});

it('can get all sold vehicles', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer ' . $this->token,
    ])->getJson('/api/sales/');

    expect($response->status())->toBe(Response::HTTP_OK)
        ->and($response->json('status'))->toBe('success');
});

afterAll(function () {
    /**
     * Clean up the database after all tests have been completed.
     * Using the truncate method because Laravel MongoDB does not support the database testing traits.
     */
    User::query()->truncate();
    Car::query()->truncate();
    VehicleTransaction::query()->truncate();
});
