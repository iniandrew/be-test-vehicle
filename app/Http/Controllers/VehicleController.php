<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehicle\VehicleRequest;
use App\Http\Requests\Vehicle\SellVehicleRequest;
use App\Http\Responses\ApiResponse;
use App\Services\VehicleService;
use App\Services\VehicleTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function __construct(
        protected VehicleService $vehicleService,
        protected VehicleTransactionService $transactionService
    ) { }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $vehicles = $this->vehicleService->getAllVehicle();

            return ApiResponse::successResponse(
                $vehicles->toArray(),
                $vehicles->isEmpty() ? 204 : 200
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    public function checkStock(string $id): JsonResponse
    {
        try {
            $stock = $this->vehicleService->getVehicleStock($id);
            return ApiResponse::successResponse(
                ['stock' => $stock],
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleRequest $request): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->createVehicle($request->validated());
            return ApiResponse::successResponse(
                $vehicle->toArray(),
                201,
                'Vehicle created successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->getVehicleById($id);
            return ApiResponse::successResponse(
                $vehicle->toArray(),
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->updateVehicle($id, $request->all());
            return ApiResponse::successResponse(
                $vehicle->toArray(),
                200,
                'Vehicle updated successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->vehicleService->deleteVehicle($id);
            return ApiResponse::successResponse([], 200, 'Vehicle deleted successfully');
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    /**
     * @param SellVehicleRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function sell(SellVehicleRequest $request, string $id): JsonResponse
    {
        try {
            $transaction = $this->transactionService->checkout($id, $request->input('quantity'));
            return ApiResponse::successResponse(
                $transaction->toArray(),
                201,
                'Transaction created successfully'
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    /**
     * Get all vehicle transactions
     * @return JsonResponse
     */
    public function sales(): JsonResponse
    {
        try {
            $transactions = $this->transactionService->getAllVehicleTransactions();
            return ApiResponse::successResponse(
                $transactions->toArray(),
                $transactions->isEmpty() ? 204 : 200
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    /**
     * Get sales report for a specific vehicle
     * @param string $id
     * @return JsonResponse
     */
    public function salesReport(string $id): JsonResponse
    {
        try {
            $report = $this->transactionService->getSalesReport($id);
            return ApiResponse::successResponse(
                $report,
            );
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }
}
