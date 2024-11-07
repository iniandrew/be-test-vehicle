<?php

namespace App\Http\Controllers;

use App\Http\Responses\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class Controller
{
    protected function handleApiResponse(callable $callback): JsonResponse
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            return ApiResponse::errorResponse($e->getMessage(), $e->getCode() ?: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
