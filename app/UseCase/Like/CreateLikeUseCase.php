<?php

namespace App\UseCase\Like;

use App\Domain\Repository\LikeRepositoryInterface;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\Like\Like;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\DomainService\LikeDomainServiceInterface;
use Exception;
use App\Domain\DomainService\MatchingDomainServiceInterface;
use App\Domain\Models\Match\Matching;
use App\Domain\Repository\MatchingRepositoryInterface;
use App\Domain\Repository\TransactionRepositoryInterface;
use App\Domain\Models\TalkRoom\TalkRoom;
use App\Domain\Models\TalkRoom\LastMessage;
use App\Domain\Repository\TalkRoomRepositoryInterface;
class CreateLikeUseCase
{
  public function __construct(
    private readonly LikeRepositoryInterface $likeRepository,
    private readonly UserRepositoryInterface $userRepository,
    private readonly LikeDomainServiceInterface $likeDomainService,
    private readonly MatchingDomainServiceInterface $matchingDomainService,
    private readonly MatchingRepositoryInterface $matchingRepository,
    private readonly TransactionRepositoryInterface $transactionRepository,
    private readonly TalkRoomRepositoryInterface $talkRoomRepository
  ) {
  }

  public function execute(array $input): void
  {
    $this->transactionRepository->transaction(function () use ($input) {
      $userId = UuidVo::NewUuidByVal($input['user_id']);
      $targetUserId = UuidVo::NewUuidByVal($input['target_user_id']);

      if ($userId->equals($targetUserId)) {
        throw new Exception('User and target user cannot be the same');
      }

      $user = $this->userRepository->findById($userId);
      $targetUser = $this->userRepository->findById($targetUserId);
      if ($user === null) {
        throw new Exception('User not found');
      }

      if ($targetUser === null) {
        throw new Exception('Target user not found');
      }

      // 既にいいねが存在していたらエラーを返す
      $isLikeFromMeExists = $this->likeDomainService->isLikeExists($userId, $targetUserId);
      if ($isLikeFromMeExists) {
        throw new Exception('Like from me already exists');
      }

      $likeFromMe = new Like(UuidVo::create(), $user->getUserId(), $targetUser->getUserId());
      $this->likeRepository->create($likeFromMe);

      // おたがいにいいねしていたらマッチングを成立させる
      $isLikeFromTargetUserExists = $this->likeDomainService->isLikeExists($targetUserId, $userId);
      $isMatchingExists = $this->matchingDomainService->isMatchingExists($userId, $targetUserId);

      if ($isLikeFromTargetUserExists && !$isMatchingExists) {
        $matching = new Matching(UuidVo::create(), $user->getUserId(), $targetUser->getUserId());
        $talkRoom = new TalkRoom(UuidVo::create(), $user->getUserId(), $targetUser->getUserId(), new LastMessage(''));
        $this->matchingRepository->create($matching);
        $this->talkRoomRepository->create($talkRoom);
      }
    });
  }
}