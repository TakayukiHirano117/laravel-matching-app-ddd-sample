<?php

namespace App\Domain\Repository;

use App\Domain\Models\User\User;
use App\Domain\Models\User\UserId;
interface UserRepositoryInterface
{
    public function create(User $user, string $password): void;
    public function findById(UserId $userId): ?User;
}