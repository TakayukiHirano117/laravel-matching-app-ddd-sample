<?php

use App\Domain\Models\Like\Like;
use App\Domain\Models\vo\UuidVo;

test('Likeインスタンスを生成できる', function () {
  $likeId = UuidVo::create();
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  $like = new Like($likeId, $userId, $targetUserId);

  expect($like)->toBeInstanceOf(Like::class);
});

test('getLikeIdで正しいいいねIDを取得できる', function () {
  $likeId = UuidVo::create();
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  $like = new Like($likeId, $userId, $targetUserId);

  expect($like->getLikeId())->toBe($likeId)
    ->and($like->getLikeId()->value())->toBe($likeId->value());
});

test('getUserIdで正しいユーザーIDを取得できる', function () {
  $likeId = UuidVo::create();
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  $like = new Like($likeId, $userId, $targetUserId);

  expect($like->getUserId())->toBe($userId)
    ->and($like->getUserId()->value())->toBe($userId->value());
});

test('getTargetUserIdで正しいターゲットユーザーIDを取得できる', function () {
  $likeId = UuidVo::create();
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  $like = new Like($likeId, $userId, $targetUserId);

  expect($like->getTargetUserId())->toBe($targetUserId)
    ->and($like->getTargetUserId()->value())->toBe($targetUserId->value());
});

test('すべてのプロパティが正しく設定される', function () {
  $likeId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440000');
  $userId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440001');
  $targetUserId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440002');

  $like = new Like($likeId, $userId, $targetUserId);

  expect($like->getLikeId()->value())->toBe('550e8400-e29b-41d4-a716-446655440000')
    ->and($like->getUserId()->value())->toBe('550e8400-e29b-41d4-a716-446655440001')
    ->and($like->getTargetUserId()->value())->toBe('550e8400-e29b-41d4-a716-446655440002');
});

test('異なるいいねIDを持つLikeは異なるLikeとして扱われる', function () {
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  $like1 = new Like(UuidVo::create(), $userId, $targetUserId);
  $like2 = new Like(UuidVo::create(), $userId, $targetUserId);

  expect($like1->getLikeId()->equals($like2->getLikeId()))->toBeFalse();
});

test('同じユーザーIDを持つ複数のLikeを作成できる', function () {
  $userId = UuidVo::create();
  $targetUserId1 = UuidVo::create();
  $targetUserId2 = UuidVo::create();

  $like1 = new Like(UuidVo::create(), $userId, $targetUserId1);
  $like2 = new Like(UuidVo::create(), $userId, $targetUserId2);

  expect($like1->getUserId()->equals($like2->getUserId()))->toBeTrue()
    ->and($like1->getTargetUserId()->equals($like2->getTargetUserId()))->toBeFalse();
});

test('同じターゲットユーザーIDを持つ複数のLikeを作成できる', function () {
  $targetUserId = UuidVo::create();
  $userId1 = UuidVo::create();
  $userId2 = UuidVo::create();

  $like1 = new Like(UuidVo::create(), $userId1, $targetUserId);
  $like2 = new Like(UuidVo::create(), $userId2, $targetUserId);

  expect($like1->getTargetUserId()->equals($like2->getTargetUserId()))->toBeTrue()
    ->and($like1->getUserId()->equals($like2->getUserId()))->toBeFalse();
});

test('ユーザーIDとターゲットユーザーIDが同じでもいいねを作成できる', function () {
  $userId = UuidVo::create();
  $targetUserId = $userId; // 同じID

  $like = new Like(UuidVo::create(), $userId, $targetUserId);

  expect($like->getUserId()->equals($like->getTargetUserId()))->toBeTrue();
});

