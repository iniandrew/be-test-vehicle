<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Exception;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    /**
     * @throws Exception
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->register($request->validated());
            return ApiResponse::successResponse($user->toArray(), 201, 'User registered successfully');
        } catch (Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $token = $this->authService->login($request->only('email', 'password'));
            return ApiResponse::successResponse($this->tokenResponse($token), 200, 'User logged in successfully');
        } catch (Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();
            return ApiResponse::successResponse([], 200, 'User logged out successfully');
        } catch (Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = $this->authService->refresh();
            return ApiResponse::successResponse($this->tokenResponse($token), 200, 'Token refreshed successfully');
        } catch (Exception $e) {
            return ApiResponse::errorResponse($e->getMessage());
        }
    }

    private function tokenResponse($token): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }
}
