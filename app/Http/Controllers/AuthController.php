<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Responses\ApiResponse;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService
    ) {}

    public function store(RegisterRequest $request): JsonResponse
    {
        return $this->handleApiResponse(function () use ($request) {
            $user = $this->authService->register($request->validated());
            return ApiResponse::successResponse($user->toArray(), Response::HTTP_CREATED, 'User registered successfully');
        });
    }

    public function login(LoginRequest $request): JsonResponse
    {
        return $this->handleApiResponse(function () use ($request) {
            $token = $this->authService->login($request->validated());
            return ApiResponse::successResponse($this->tokenResponse($token), Response::HTTP_OK, 'User logged in successfully');
        });
    }

    public function logout(): JsonResponse
    {
        return $this->handleApiResponse(function () {
            $this->authService->logout();
            return ApiResponse::successResponse([], Response::HTTP_OK, 'User logged out successfully');
        });
    }

    public function refresh(): JsonResponse
    {
        return $this->handleApiResponse(function () {
            $token = $this->authService->refresh();
            return ApiResponse::successResponse($this->tokenResponse($token), Response::HTTP_OK, 'Token refreshed successfully');
        });
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
