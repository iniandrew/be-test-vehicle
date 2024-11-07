<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\VehicleTransaction;
use App\Repositories\VehicleRepository;
use App\Repositories\VehicleTransactionRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class VehicleTransactionService
{
    public function __construct(
        protected VehicleRepository $vehicleRepository,
        protected VehicleTransactionRepository $vehicleTransactionRepository
    ) {}

    /**
     * @throws ApiException
     * @return Collection
     */
    public function getAllVehicleTransactions(): Collection
    {
        try {
            return $this->vehicleTransactionRepository->getAll();
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param string $vehicleId
     * @param int $quantity
     * @throws \Exception
     * @return VehicleTransaction
     */
    public function checkout(string $vehicleId, int $quantity): VehicleTransaction
    {
        try {
            $vehicle = $this->vehicleRepository->findById($vehicleId)
                ?: throw new ApiException('Vehicle not found', Response::HTTP_NOT_FOUND);

            $stock = $vehicle->stock;

            if ($stock < $quantity) {
                throw new \Exception('Not enough stock', Response::HTTP_BAD_REQUEST);
            }

            $this->vehicleRepository->update($vehicleId, [
                'stock' => $stock - $quantity
            ]);

            return $this->vehicleTransactionRepository->create([
                'vehicle_id' => $vehicleId,
                'quantity' => $quantity,
                'transaction_date' => now()
            ]);
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws ApiException
     */
    public function getSalesReport(string $vehicleId): array
    {
        try {
            $transactions = $this->vehicleTransactionRepository->findByVehicleId($vehicleId);
            $vehicle = $this->vehicleRepository->findById($vehicleId)
                ?: throw new ApiException('Vehicle not found', Response::HTTP_NOT_FOUND);

            $totalSales = $transactions->sum('quantity');
            $totalRevenue = $transactions->sum(fn ($transaction) => $transaction->quantity * $vehicle->price);

            if ($totalSales === 0) {
                throw new ApiException('No sales found', Response::HTTP_NOT_FOUND);
            }

            return [
                'vehicle' => $vehicle,
                'total_sales' => $totalSales,
                'total_revenue' => $totalRevenue
            ];
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }
}
