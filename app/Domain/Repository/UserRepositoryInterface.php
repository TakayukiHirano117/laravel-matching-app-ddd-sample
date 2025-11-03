<?php

namespace App\Domain\Repository;

use App\Domain\Models\User\User;
use App\Domain\Models\vo\UuidVo;
interface UserRepositoryInterface
{
    public function signUp(User $user, string $password): string;
    public function findById(UuidVo $userId): ?User;
    public function findByEmail(string $email): ?User;
    public function verifyPassword(User $user, string $password): bool;
    public function createToken(User $user, string $tokenName = 'api_token'): string;
}