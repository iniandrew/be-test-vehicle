<?php

namespace App\Repositories;

use App\Models\VehicleTransaction;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class VehicleTransactionRepository
{
    public function __construct(
        protected VehicleTransaction $model
    ) { }

    public function getAll(): Collection
    {
        return Cache::remember('transactions', now()->addMinutes(60), function () {
            return $this->model->newQuery()->get();
        });
    }

    public function create(array $data): VehicleTransaction
    {
        $transaction = $this->model->newQuery()->create($data);

        Cache::forget("transaction-{$data["vehicle_id"]}");
        Cache::forget('transactions');

        return $transaction;
    }

    public function findByVehicleId(string $vehicleId): Collection
    {
        return Cache::remember("transaction-{$vehicleId}", now()->addMinutes(60), function () use ($vehicleId) {
            return $this->model->newQuery()->where('vehicle_id', $vehicleId)->get();
        });
    }
}
