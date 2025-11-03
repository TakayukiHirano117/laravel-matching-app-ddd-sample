<?php

namespace App\UseCase\Auth;

use App\Domain\Repository\UserRepositoryInterface;
use Exception;

class SignInUseCase
{
  public function __construct(
    private readonly UserRepositoryInterface $userRepository
  ) {
  }

  /**
   * @param array<string, string> $input
   * @return string トークン
   * @throws Exception
   */
  public function execute(array $input): string
  {
    $email = $input['email'];
    $password = $input['password'];

    $user = $this->userRepository->findByEmail($email);

    if ($user === null) {
      throw new Exception('Invalid credentials');
    }

    if (!$this->userRepository->verifyPassword($user, $password)) {
      throw new Exception('Invalid credentials');
    }

    return $this->userRepository->createToken($user);
  }
}

