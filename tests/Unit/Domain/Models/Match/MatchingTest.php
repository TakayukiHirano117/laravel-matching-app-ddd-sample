<?php

use App\Domain\Models\Match\Matching;
use App\Domain\Models\vo\UuidVo;

test('Matchingインスタンスを生成できる', function () {
  $matchingId = UuidVo::create();
  $userId = UuidVo::create();
  $otherUserId = UuidVo::create();

  $matching = new Matching($matchingId, $userId, $otherUserId);

  expect($matching)->toBeInstanceOf(Matching::class);
});

test('getMatchingIdで正しいマッチングIDを取得できる', function () {
  $matchingId = UuidVo::create();
  $userId = UuidVo::create();
  $otherUserId = UuidVo::create();

  $matching = new Matching($matchingId, $userId, $otherUserId);

  expect($matching->getMatchingId())->toBe($matchingId)
    ->and($matching->getMatchingId()->value())->toBe($matchingId->value());
});

test('getUserIdで正しいユーザーIDを取得できる', function () {
  $matchingId = UuidVo::create();
  $userId = UuidVo::create();
  $otherUserId = UuidVo::create();

  $matching = new Matching($matchingId, $userId, $otherUserId);

  expect($matching->getUserId())->toBe($userId)
    ->and($matching->getUserId()->value())->toBe($userId->value());
});

test('getOtherUserIdで正しい他のユーザーIDを取得できる', function () {
  $matchingId = UuidVo::create();
  $userId = UuidVo::create();
  $otherUserId = UuidVo::create();

  $matching = new Matching($matchingId, $userId, $otherUserId);

  expect($matching->getOtherUserId())->toBe($otherUserId)
    ->and($matching->getOtherUserId()->value())->toBe($otherUserId->value());
});

test('すべてのプロパティが正しく設定される', function () {
  $matchingId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440000');
  $userId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440001');
  $otherUserId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440002');

  $matching = new Matching($matchingId, $userId, $otherUserId);

  expect($matching->getMatchingId()->value())->toBe('550e8400-e29b-41d4-a716-446655440000')
    ->and($matching->getUserId()->value())->toBe('550e8400-e29b-41d4-a716-446655440001')
    ->and($matching->getOtherUserId()->value())->toBe('550e8400-e29b-41d4-a716-446655440002');
});

test('NewMatchingByValで正しくインスタンスを生成できる', function () {
  $matchingId = UuidVo::create();
  $userId = UuidVo::create();
  $otherUserId = UuidVo::create();

  $matching = Matching::NewMatchingByVal($matchingId, $userId, $otherUserId);

  expect($matching)->toBeInstanceOf(Matching::class)
    ->and($matching->getMatchingId())->toBe($matchingId)
    ->and($matching->getUserId())->toBe($userId)
    ->and($matching->getOtherUserId())->toBe($otherUserId);
});

test('異なるマッチングIDを持つMatchingは異なるMatchingとして扱われる', function () {
  $userId = UuidVo::create();
  $otherUserId = UuidVo::create();

  $matching1 = new Matching(UuidVo::create(), $userId, $otherUserId);
  $matching2 = new Matching(UuidVo::create(), $userId, $otherUserId);

  expect($matching1->getMatchingId()->equals($matching2->getMatchingId()))->toBeFalse();
});

test('同じユーザーIDを持つ複数のMatchingを作成できる', function () {
  $userId = UuidVo::create();
  $otherUserId1 = UuidVo::create();
  $otherUserId2 = UuidVo::create();

  $matching1 = new Matching(UuidVo::create(), $userId, $otherUserId1);
  $matching2 = new Matching(UuidVo::create(), $userId, $otherUserId2);

  expect($matching1->getUserId()->equals($matching2->getUserId()))->toBeTrue()
    ->and($matching1->getOtherUserId()->equals($matching2->getOtherUserId()))->toBeFalse();
});

test('同じ他のユーザーIDを持つ複数のMatchingを作成できる', function () {
  $otherUserId = UuidVo::create();
  $userId1 = UuidVo::create();
  $userId2 = UuidVo::create();

  $matching1 = new Matching(UuidVo::create(), $userId1, $otherUserId);
  $matching2 = new Matching(UuidVo::create(), $userId2, $otherUserId);

  expect($matching1->getOtherUserId()->equals($matching2->getOtherUserId()))->toBeTrue()
    ->and($matching1->getUserId()->equals($matching2->getUserId()))->toBeFalse();
});

