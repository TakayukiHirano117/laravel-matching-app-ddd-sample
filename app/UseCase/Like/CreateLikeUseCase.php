<?php

namespace App\UseCase\Like;

use App\Domain\Repository\LikeRepositoryInterface;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\Like\Like;
use App\Domain\Repository\UserRepositoryInterface;
use Ramsey\Uuid\Uuid;
use App\Domain\DomainService\LikeDomainServiceInterface;
use Exception;
class CreateLikeUseCase
{
  public function __construct(
    private readonly LikeRepositoryInterface $likeRepository,
    private readonly UserRepositoryInterface $userRepository,
    private readonly LikeDomainServiceInterface $likeDomainService
  ) {
  }

  public function execute(array $input): void
  {
    $userId = UuidVo::NewUuidByVal($input['user_id']);
    $targetUserId = UuidVo::NewUuidByVal($input['target_user_id']);

    $user = $this->userRepository->findById($userId);
    $targetUser = $this->userRepository->findById($targetUserId);

    if ($user === null) {
      throw new Exception('User not found');
    }

    if ($targetUser === null) {
      throw new Exception('Target user not found');
    }

    // 既にいいねが存在していたらエラーを返す
    if ($this->likeDomainService->isLikeExists($userId, $targetUserId)) {
      throw new Exception('Like already exists');
    }

    $like = new Like(UuidVo::create(), $user->getUserId(), $targetUser->getUserId());
    $this->likeRepository->create($like);
  }
}