<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use Illuminate\Support\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VehicleService
{
    public function __construct(
        protected VehicleRepository $vehicleRepository
    ) { }

    public function getAllVehicle(): Collection
    {
        return $this->vehicleRepository->getAll();
    }

    public function getVehicleById(string $id): Vehicle
    {
        $vehicle = $this->vehicleRepository->findById($id);

        if (!$vehicle) {
            throw new NotFoundHttpException('Vehicle not found');
        }

        return $vehicle;
    }

    /**
     * @throws \Exception
     */
    public function createVehicle(array $data): Vehicle
    {
        return $this->vehicleRepository->create($data);
    }

    public function getVehicleStock(string $id): int
    {
        $vehicle = $this->getVehicleById($id);

        return $vehicle->stock;
    }

    public function updateVehicle(string $id, array $data): Vehicle
    {
        $vehicle = $this->getVehicleById($id);

        return $this->vehicleRepository->update($vehicle, $data);
    }

    public function deleteVehicle(string $id): bool
    {
        $vehicle = $this->getVehicleById($id);

        return $this->vehicleRepository->delete($vehicle->id);
    }
}
