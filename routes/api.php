<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;


Route::middleware('api')->prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'store']);
});

Route::middleware('jwt')->prefix('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

Route::middleware('jwt')->group(function () {
    Route::get('sales', [VehicleController::class, 'sales']);
    Route::apiResource('vehicles', VehicleController::class);

    Route::group(['prefix' => 'vehicles'], function () {
        Route::get('{vehicle}/stock', [VehicleController::class, 'checkStock']);
        Route::post('{vehicle}/sell', [VehicleController::class, 'sell']);
        Route::get('{vehicle}/sales-report', [VehicleController::class, 'salesReport']);
    });
});
