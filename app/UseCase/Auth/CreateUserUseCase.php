<?php

namespace App\UseCase\Auth;

use App\Domain\Models\User\User;
use App\Domain\Repository\UserRepositoryInterface;

class CreateUserUseCase
{
    /**
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * @param User $user
     */
    public function execute(User $user, string $password): void
    {
        // ユーザー登録ロジックを実行する
        $this->userRepository->create($user, $password);
    }
}