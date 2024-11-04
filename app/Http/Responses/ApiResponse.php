<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param array|null $data
     * @param int $statusCode
     * @param string $message
     * @return JsonResponse
     */
    public static function successResponse(
        ?array $data,
        int    $statusCode,
        string $message
    ): JsonResponse {
        $response = [
            'rc' => $statusCode,
            'status' => 'success',
            'message' => $message
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return new JsonResponse($response, $statusCode);
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public static function errorResponse(
        string $message,
        int $statusCode = 400
    ): JsonResponse {
        return new JsonResponse([
            'rc' => $statusCode,
            'status' => 'error',
            'message' => $message
        ], $statusCode);
    }
}
