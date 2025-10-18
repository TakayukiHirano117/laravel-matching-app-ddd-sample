<?php

namespace App\Domain\Repository;

use App\Domain\Models\User\User;

interface UserRepositoryInterface
{
    public function create(User $user, string $password): void;
}