<?php

namespace App\UseCase\User;

use App\UseCase\QueryService\UserQueryServiceInterface;

class GetUserListUseCase
{
  public function __construct(
    private readonly UserQueryServiceInterface $userQueryService
  ) {
  }

  public function execute(): array
  {
    return $this->userQueryService->getUserList();
  }
}