<?php

namespace App\UseCase\Like;

use App\Domain\Repository\LikeRepositoryInterface;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\Like\Like;
use App\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use App\Domain\DomainService\LikeDomainServiceInterface;
class CreateLikeUseCase
{
  public function __construct(
    private readonly LikeRepositoryInterface $likeRepository,
    private readonly UserRepositoryInterface $userRepository,
    private readonly LikeDomainServiceInterface $likeDomainService
  ) {
  }

  public function execute(UuidVo $userId, UuidVo $targetUserId): void
  {
    $user = $this->userRepository->findById($userId);
    $targetUser = $this->userRepository->findById($targetUserId);

    if ($user === null || $targetUser === null) {
      throw new \Exception('User not found');
    }

    // 既にいいねが存在していたらエラーを返す
    if ($this->likeDomainService->isLikeExists($userId, $targetUserId)) {
      throw new \Exception('Like already exists');
    }

    $like = new Like(new UuidVo(Uuid::uuid4()->toString()), $user->getUserId(), $targetUser->getUserId());
    $this->likeRepository->create($like);
  }
}