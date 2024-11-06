<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository
{
    public function __construct(
        protected User $model
    ) { }

    /**
     * Create a new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User
    {
        $model = $this->model->newQuery()->create($data);
        return $model->fresh();
    }

    /**
     * Find user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->newQuery()->where('email', $email)->first();
    }
}
