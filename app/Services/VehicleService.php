<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

class VehicleService
{
    public function __construct(
        protected VehicleRepository $vehicleRepository
    ) { }

    /**
     * @return Collection
     * @throws ApiException
     */
    public function getAllVehicle(): Collection
    {
        try {
            return $this->vehicleRepository->getAll();
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param string $id
     * @return Vehicle
     * @throws ApiException
     */
    public function getVehicleById(string $id): Vehicle
    {
        return $this->vehicleRepository->findById($id)
            ?: throw new ApiException('Vehicle not found', Response::HTTP_NOT_FOUND);
    }

    /**
     * @param array $data
     * @throws \Exception
     * @return Vehicle
     */
    public function createVehicle(array $data): Vehicle
    {
        try {
            return $this->vehicleRepository->create($data);
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param string $id
     * @throws ApiException
     * @return int
     */
    public function getVehicleStock(string $id): int
    {
        return $this->getVehicleById($id)->stock;
    }

    /**
     * @param string $id
     * @param array $data
     * @throws ApiException
     * @return Vehicle
     */
    public function updateVehicle(string $id, array $data): Vehicle
    {
        try {
            $vehicle = $this->getVehicleById($id);
            return $this->vehicleRepository->update($vehicle->id, $data);
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $id
     * @throws ApiException
     * @return bool
     */
    public function deleteVehicle(string $id): bool
    {
        try {
            $vehicle = $this->getVehicleById($id);
            return $this->vehicleRepository->delete($vehicle->id);
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage(), $e->getCode());
        }
    }
}
