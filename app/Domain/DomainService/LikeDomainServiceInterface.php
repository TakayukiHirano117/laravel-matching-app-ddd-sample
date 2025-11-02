<?php

namespace App\Domain\DomainService;

use App\Domain\Models\vo\UuidVo;

interface LikeDomainServiceInterface
{
  public function isLikeExists(UuidVo $userId, UuidVo $targetUserId): bool;
}