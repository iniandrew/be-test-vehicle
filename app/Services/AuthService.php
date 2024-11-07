<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\User;
use App\Repositories\AuthRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(
        protected AuthRepository $authRepository
    ) {}

    /**
     * @param array $data
     * @return User
     * @throws ApiException
     */
    public function register(array $data): User
    {
        try {
            $data['password'] = Hash::make($data['password']);
            return $this->authRepository->create($data);
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @return string
     * @throws ApiException
     */
    public function login(array $data): string
    {
        try {
            $user = $this->authRepository->findByEmail($data['email']);

            if (!$user) {
                throw new ApiException('User not found', Response::HTTP_NOT_FOUND);
            }

            if (!Hash::check($data['password'], $user->password)) {
                throw new ApiException('Invalid credentials', Response::HTTP_UNAUTHORIZED);
            }

            return JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @throws ApiException
     */
    public function logout()
    {
        try {
            return JWTAuth::invalidate(JWTAuth::getToken());
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

    /**
     * @throws ApiException
     * @return string
     */
    public function refresh(): string
    {
        try {
            return JWTAuth::refresh(JWTAuth::getToken());
        } catch (\Exception $e) {
            throw new ApiException($e->getMessage());
        }
    }

}
