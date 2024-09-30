<?php

namespace App\Repositories;

use App\Models\User;

class AuthRepository extends BaseRepository
{
    public function getModel()
    {
        return User::class;
    }

    public function findByEmail($email)
    {
        return $this->model->where('email', $email)->first();
    }
}
