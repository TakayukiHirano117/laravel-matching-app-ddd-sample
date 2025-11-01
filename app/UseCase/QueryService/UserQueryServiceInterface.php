<?php

namespace App\UseCase\QueryService;

interface UserQueryServiceInterface
{
  public function getUserList(): array;
}