<?php

namespace App\Repositories;

use App\Models\VehicleTransaction;
use Illuminate\Support\Collection;

class VehicleTransactionRepository
{
    public function __construct(
        protected VehicleTransaction $model
    ) { }

    public function getAll(): Collection
    {
        return $this->model->newQuery()->get();
    }

    public function create(array $data): VehicleTransaction
    {
        return $this->model->newQuery()->create($data);
    }

    public function findByVehicleId(string $vehicleId): Collection
    {
        return $this->model->newQuery()->where('vehicle_id', $vehicleId)->get();
    }
}
