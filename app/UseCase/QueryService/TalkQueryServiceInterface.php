<?php

namespace App\UseCase\QueryService;

use App\Domain\Models\vo\UuidVo;

interface TalkQueryServiceInterface
{
  public function getTalkRooms(UuidVo $userId): ?array;
}