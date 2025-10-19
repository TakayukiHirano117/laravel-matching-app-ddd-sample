<?php

namespace App\UseCase\Like;

use App\Domain\Repository\LikeRepositoryInterface;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\Like\Like;
class CreateLikeUseCase
{
  public function __construct(
    private readonly LikeRepositoryInterface $likeRepository
  ) {
  }

  public function execute(UuidVo $userId, UuidVo $targetUserId): void
  {
    $like = new Like($userId, $targetUserId);
    $this->likeRepository->create($like);
  }
}