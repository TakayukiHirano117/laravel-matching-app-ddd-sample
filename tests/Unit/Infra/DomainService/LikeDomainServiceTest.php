<?php

use App\Domain\Models\vo\UuidVo;
use App\Infra\DomainService\LikeDomainService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

uses(RefreshDatabase::class);

test('いいねが存在する場合にtrueを返す', function () {
  $likeDomainService = new LikeDomainService();
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  // テスト用のユーザーを作成
  DB::table('users')->insert([
    'id' => $userId->value(),
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $targetUserId->value(),
    'name' => 'Target User',
    'email' => 'target@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  // いいねを追加
  DB::table('likes')->insert([
    'id' => UuidVo::create()->value(),
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId->value(),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $result = $likeDomainService->isLikeExists($userId, $targetUserId);

  expect($result)->toBeTrue();
});

test('いいねが存在しない場合にfalseを返す', function () {
  $likeDomainService = new LikeDomainService();
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  // テスト用のユーザーを作成
  DB::table('users')->insert([
    'id' => $userId->value(),
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $targetUserId->value(),
    'name' => 'Target User',
    'email' => 'target@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  // いいねは追加しない

  $result = $likeDomainService->isLikeExists($userId, $targetUserId);

  expect($result)->toBeFalse();
});

test('異なるユーザーIDの組み合わせでfalseを返す', function () {
  $likeDomainService = new LikeDomainService();
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();
  $otherUserId = UuidVo::create();

  // テスト用のユーザーを作成
  DB::table('users')->insert([
    'id' => $userId->value(),
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $targetUserId->value(),
    'name' => 'Target User',
    'email' => 'target@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $otherUserId->value(),
    'name' => 'Other User',
    'email' => 'other@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  // 異なるユーザーIDの組み合わせでいいねを追加
  DB::table('likes')->insert([
    'id' => UuidVo::create()->value(),
    'user_id' => $userId->value(),
    'target_user_id' => $otherUserId->value(),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $result = $likeDomainService->isLikeExists($userId, $targetUserId);

  expect($result)->toBeFalse();
});
