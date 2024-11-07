<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vehicle\VehicleRequest;
use App\Http\Requests\Vehicle\SellVehicleRequest;
use App\Http\Responses\ApiResponse;
use App\Services\VehicleService;
use App\Services\VehicleTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

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
        return $this->handleApiResponse(function () {
            $vehicles = $this->vehicleService->getAllVehicle();

            return ApiResponse::successResponse(
                $vehicles->toArray(),
                $vehicles->isEmpty()
                    ? Response::HTTP_NO_CONTENT
                    : Response::HTTP_OK
            );
        });
    }

    public function checkStock(string $id): JsonResponse
    {
        return $this->handleApiResponse(function () use ($id) {
            $stock = $this->vehicleService->getVehicleStock($id);
            return ApiResponse::successResponse(
                ['stock' => $stock],
            );
        });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VehicleRequest $request): JsonResponse
    {
        return $this->handleApiResponse(function () use ($request) {
            $vehicle = $this->vehicleService->createVehicle($request->validated());

            return ApiResponse::successResponse(
                $vehicle->toArray(),
                Response::HTTP_CREATED,
                'Vehicle created successfully'
            );
        });
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        return $this->handleApiResponse(function () use ($id) {
            $vehicle = $this->vehicleService->getVehicleById($id);
            return ApiResponse::successResponse($vehicle->toArray());
        });
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VehicleRequest $request, string $id): JsonResponse
    {
        return $this->handleApiResponse(function () use ($request, $id) {
            $vehicle = $this->vehicleService->updateVehicle($id, $request->validated());

            return ApiResponse::successResponse(
                $vehicle->toArray(),
                Response::HTTP_OK,
                'Vehicle updated successfully'
            );
        });
    }

    /**
     * Remove the specified resource from storage.
     * @param string $id
     */
    public function destroy(string $id): JsonResponse
    {
        return $this->handleApiResponse(function () use ($id) {
            $this->vehicleService->deleteVehicle($id);
            return ApiResponse::successResponse([], Response::HTTP_OK, 'Vehicle deleted successfully');
        });
    }

    /**
     * @param SellVehicleRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function sell(SellVehicleRequest $request, string $id): JsonResponse
    {
        return $this->handleApiResponse(function () use ($request, $id) {
            $transaction = $this->transactionService->checkout($id, $request->input('quantity'));

            return ApiResponse::successResponse(
                $transaction->toArray(),
                Response::HTTP_CREATED,
                'Vehicle sold successfully'
            );
        });
    }

    /**
     * Get all vehicle transactions
     * @return JsonResponse
     */
    public function sales(): JsonResponse
    {
        return $this->handleApiResponse(function () {
            $transactions = $this->transactionService->getAllVehicleTransactions();

            return ApiResponse::successResponse(
                $transactions->toArray(),
                $transactions->isEmpty()
                    ? Response::HTTP_NO_CONTENT
                    : Response::HTTP_OK
            );
        });
    }

    /**
     * Get sales report for a specific vehicle
     * @param string $id
     * @return JsonResponse
     */
    public function salesReport(string $id): JsonResponse
    {
        return $this->handleApiResponse(function () use ($id) {
            $report = $this->transactionService->getSalesReport($id);
            return ApiResponse::successResponse($report);
        });
    }
}
