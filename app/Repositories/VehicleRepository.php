<?php

namespace App\Repositories;

use App\Enums\VehicleType;
use App\Models\Car;
use App\Models\Motorcycle;
use App\Models\Vehicle;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class VehicleRepository implements VehicleRepositoryInterface
{
    public function __construct(
        protected Car $car,
        protected Motorcycle $motorcycle,
    ) {}

    public function getAll(): Collection
    {
        $cars = $this->car->newQuery()->get();
        $motorcycles = $this->motorcycle->newQuery()->get();

        return Cache::remember('vehicles', now()->addMinutes(60), function () use ($cars, $motorcycles) {
            return $cars->merge($motorcycles);
        });
    }

    public function findById(string $id): ?Vehicle
    {
        return Cache::remember("vehicle-{$id}", now()->addMinutes(60), function () use ($id) {
            return $this->car->newQuery()->find($id) ?? $this->motorcycle->newQuery()->find($id);
        });
    }

    /**
     * @throws \Exception
     */
    public function create(array $data): Vehicle
    {
        $vehicle = match ($data['type']) {
            VehicleType::Car->value => $this->car->newQuery()->create($data),
            VehicleType::Motorcycle->value => $this->motorcycle->newQuery()->create($data),
            default => throw new \Exception('Invalid vehicle type')
        };

        Cache::forget('vehicles');

        return $vehicle;
    }

    public function update(string $id, array $data): Vehicle
    {
        $model = $this->findById($id);
        $model->update($data);

        Cache::forget("vehicle-{$id}");
        Cache::forget('vehicles');

        return $model;
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);

        Cache::forget("vehicle-{$id}");
        Cache::forget('vehicles');

        return $model->delete();
    }
}
