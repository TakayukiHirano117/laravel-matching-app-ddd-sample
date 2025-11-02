<?php

namespace App\Infra\DomainService;

use App\Domain\DomainService\LikeDomainServiceInterface;
use App\Domain\Models\vo\UuidVo;
use Illuminate\Support\Facades\DB;

class LikeDomainService implements LikeDomainServiceInterface
{
  public function isLikeExists(UuidVo $userId, UuidVo $targetUserId): bool
  {
    return DB::table('likes')
      ->where('user_id', $userId->value())
      ->where('target_user_id', $targetUserId->value())
      ->exists();
  }
}