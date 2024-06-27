<?php

namespace App\Services;

use App\Repositories\AuthRepository;

class AuthService
{
    protected AuthRepository $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function createUser($attributes) {
        return $this->authRepository->create($attributes);
    }

    public function findUserById($id) {
        return $this->authRepository->findOrFail($id);
    }

    public function findUserByEmail($email) {
        return $this->authRepository->findByEmail($email);
    }
}
