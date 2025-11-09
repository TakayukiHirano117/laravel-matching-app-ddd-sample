<?php

namespace App\UseCase\User;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\User\User;

class UserUseCase
{
  public function __construct(
    private readonly UserRepositoryInterface $userRepository
  ) {
  }

  public function getMyProfile(string $userId): User
  {
    $userIdVo = UuidVo::NewUuidByVal($userId);
    if ($userIdVo === null) {
      throw new \Exception('Invalid user ID');
    }

    return $this->userRepository->getMyProfile($userIdVo);
  }
}