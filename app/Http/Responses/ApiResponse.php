<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class ApiResponse
{
    /**
     * @param array|null $data
     * @param int $statusCode
     * @param string|null $message
     * @return JsonResponse
     */
    public static function successResponse(
        ?array $data,
        int    $statusCode = 200,
        ?string $message = 'Success'
    ): JsonResponse {
        $response = [
            'code' => $statusCode,
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
        int $statusCode = 500
    ): JsonResponse {
        return new JsonResponse([
            'code' => $statusCode,
            'status' => 'error',
            'message' => $message
        ], $statusCode);
    }
}
