<?php

use App\Models\Car;
use App\Models\VehicleTransaction;
use App\Repositories\VehicleRepository;
use App\Repositories\VehicleTransactionRepository;
use App\Services\VehicleService;
use App\Services\VehicleTransactionService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->vehicleRepository = mock(VehicleRepository::class);
    $this->transactionRepository = mock(VehicleTransactionRepository::class);
    $this->vehicleService = new VehicleService($this->vehicleRepository);
    $this->transactionService = new VehicleTransactionService(
        $this->vehicleRepository,
        $this->transactionRepository
    );
});

it('can view vehicle stock', function () {
    $vehicleId = '1234567890';
    $stock = 5;
    $vehicle = new Car(['stock' => $stock]);

    $this->vehicleRepository->shouldReceive('findById')
        ->with($vehicleId)
        ->andReturn($vehicle);

    $result = $this->vehicleService->getVehicleStock($vehicleId);
    expect($result)->toBe($stock);
});

it('can sell a vehicle', function () {
    $vehicleId = '1234567890';

    $initialStock = 5;
    $quantity = 2;
    $expectedStock = $initialStock - $quantity;

    $vehicle = new Car(['stock' => $initialStock]);
    $transaction = new VehicleTransaction([
        'vehicle_id' => $vehicleId,
        'quantity' => $quantity,
    ]);

    $this->vehicleRepository->shouldReceive('findById')
        ->with($vehicleId)
        ->andReturn($vehicle);

    $this->vehicleRepository->shouldReceive('update')
        ->with($vehicleId, Mockery::on(fn ($data) => $data['stock'] === $expectedStock))
        ->andReturnUsing(function () use ($vehicle, $expectedStock) {
            $vehicle->stock = $expectedStock;
            return $vehicle;
        });

    $this->transactionRepository->shouldReceive('create')
        ->with(Mockery::on(fn ($data) => $data['vehicle_id'] === $vehicleId && $data['quantity'] === $quantity))
        ->andReturn($transaction);

    try {
        $result = $this->transactionService->checkout($vehicleId, $quantity);
        expect($result->vehicle_id)->toBe($vehicleId)
            ->and($result->quantity)->toBe($quantity);

        $currentStock = $this->vehicleService->getVehicleStock($vehicleId);
        expect($currentStock)->toBe($expectedStock);
    } catch (Exception $e) {
        expect($e->getMessage())->toBe('Not enough stock');
    }
});

it('can view sales report by specific vehicle', function () {
    $vehicleId = '1234567890';
    $transactions = collect([
        new VehicleTransaction(['quantity' => 2]),
        new VehicleTransaction(['quantity' => 3]),
    ]);

    $vehicle = new Car(['id' => $vehicleId, 'price' => 5000]);

    $this->vehicleRepository->shouldReceive('findById')
        ->with($vehicleId)
        ->andReturnUsing(function () use ($vehicle, $vehicleId) {
            $vehicle->id = $vehicleId;
            return $vehicle;
        });

    $this->transactionRepository->shouldReceive('findByVehicleId')
        ->with($vehicleId)
        ->andReturn($transactions);

    $totalSold = $transactions->sum('quantity');
    $totalRevenue = $transactions->sum(fn ($transaction) => $transaction->quantity * $vehicle->price);

    $result = $this->transactionService->getSalesReport($vehicleId);
    expect($result['vehicle']->id)->toBe($vehicleId)
        ->and($result['total_sold'])->toBe($totalSold)
        ->and($result['total_revenue'])->toBe($totalRevenue);
});

afterEach(function () {
    Mockery::close();
});
