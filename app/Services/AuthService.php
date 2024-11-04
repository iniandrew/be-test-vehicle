<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\AuthRepository;
use Exception;
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
     * @throws Exception
     */
    public function register(array $data): User
    {
        try {
            $data['password'] = Hash::make($data['password']);
            return $this->authRepository->create($data);
        } catch (Exception $e) {
            throw new \Exception('Error registering user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * @param array $data
     * @return string|\Exception
     * @throws Exception
     */
    public function login(array $data): string|\Exception
    {
        try {
            $user = $this->authRepository->findByEmail($data['email']);

            if (!$user) {
                throw new \Exception('User not found', 404);
            }

            if (!Hash::check($data['password'], $user->password)) {
                throw new \Exception('Invalid credentials', 401);
            }

            return JWTAuth::fromUser($user);
        } catch (JWTException $e) {
            throw new Exception('Could not create token', 500);
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

    /**
     * @throws Exception
     * @return array
     */
    public function logout(): array
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return ['message' => 'User logged out successfully'];
        } catch (Exception $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    /**
     * @throws Exception
     * @return string
     */
    public function refresh(): string
    {
        try {
            return JWTAuth::refresh(JWTAuth::getToken());
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }

}
