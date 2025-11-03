<?php

namespace App\Domain\DomainService;

use App\Domain\Models\vo\UuidVo;

interface MatchingDomainServiceInterface
{
  public function isMatchingExists(UuidVo $userId, UuidVo $otherUserId): bool;
}