<?php

namespace App\Services;

use App\Models\VehicleTransaction;
use App\Repositories\VehicleRepository;
use App\Repositories\VehicleTransactionRepository;
use Illuminate\Support\Collection;

class VehicleTransactionService
{
    public function __construct(
        protected VehicleRepository $vehicleRepository,
        protected VehicleTransactionRepository $vehicleTransactionRepository
    ) {}

    public function getAllVehicleTransactions(): Collection
    {
        return $this->vehicleTransactionRepository->getAll();
    }

    /**
     * @throws \Exception
     */
    public function checkout(string $vehicleId, int $quantity): VehicleTransaction
    {
        $vehicle = $this->vehicleRepository->findById($vehicleId);

        if ($vehicle->stock < $quantity) {
            throw new \Exception('Not enough stock');
        }

        $this->vehicleRepository->update($vehicleId, ['stock' => $vehicle->stock - $quantity]);

        return $this->vehicleTransactionRepository->create([
            'vehicle_id' => $vehicleId,
            'quantity' => $quantity,
            'transaction_date' => now()
        ]);
    }

    public function getSalesReport(string $vehicleId): array
    {
        $transactions = $this->vehicleTransactionRepository->findByVehicleId($vehicleId);
        $vehicle = $this->vehicleRepository->findById($vehicleId);

        $totalSold = $transactions->sum('quantity');
        $totalRevenue = $transactions->sum(fn ($transaction) => $vehicle->price * $transaction->quantity);

        return [
            'vehicle' => $vehicle,
            'total_sold' => $totalSold,
            'total_revenue' => $totalRevenue
        ];
    }
}
