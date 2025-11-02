<?php

namespace App\UseCase\Auth;

use App\Domain\Models\User\User;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\vo\Email;
use App\Domain\Models\User\UserName;
use Ramsey\Uuid\Uuid;

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
     * @param array $input
     */
    public function execute($input): string
    {
        $userId = new UuidVo(Uuid::uuid4()->toString());
        $userName = new UserName($input['name']);
        $email = new Email($input['email']);
        $user = new User($userId, $userName, $email);

        $token = $this->userRepository->signUp($user, $input['password']);
        return $token;
    }
}