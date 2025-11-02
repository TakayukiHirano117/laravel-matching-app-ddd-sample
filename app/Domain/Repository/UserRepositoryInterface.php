<?php

namespace App\Domain\Repository;

use App\Domain\Models\User\User;
use App\Domain\Models\vo\UuidVo;
interface UserRepositoryInterface
{
    public function signUp(User $user, string $password): string;
    public function findById(UuidVo $userId): ?User;
}