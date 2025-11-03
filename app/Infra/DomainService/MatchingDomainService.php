<?php

namespace App\Infra\DomainService;

use App\Domain\DomainService\MatchingDomainServiceInterface;
use App\Domain\Models\vo\UuidVo;
use Illuminate\Support\Facades\DB;

class MatchingDomainService implements MatchingDomainServiceInterface
{
  public function isMatchingExists(UuidVo $userId, UuidVo $otherUserId): bool
  {
    return DB::table('matchings')
      ->where('user_id', $userId->value())
      ->where('other_user_id', $otherUserId->value())
      ->exists();
  }
}