<?php

use App\Domain\Models\vo\UuidVo;
use App\Infra\DomainService\MatchingDomainService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('マッチングが存在する場合にtrueを返す', function () {
  $matchingDomainService = new MatchingDomainService();
  $userId = UuidVo::create();
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
    'id' => $otherUserId->value(),
    'name' => 'Other User',
    'email' => 'other@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  // マッチングを追加
  DB::table('matchings')->insert([
    'id' => UuidVo::create()->value(),
    'user_id' => $userId->value(),
    'other_user_id' => $otherUserId->value(),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $result = $matchingDomainService->isMatchingExists($userId, $otherUserId);

  expect($result)->toBeTrue();
});

test('マッチングが存在しない場合にfalseを返す', function () {
  $matchingDomainService = new MatchingDomainService();
  $userId = UuidVo::create();
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
    'id' => $otherUserId->value(),
    'name' => 'Other User',
    'email' => 'other@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  // マッチングは追加しない

  $result = $matchingDomainService->isMatchingExists($userId, $otherUserId);

  expect($result)->toBeFalse();
});

test('異なるユーザーIDの組み合わせでfalseを返す', function () {
  $matchingDomainService = new MatchingDomainService();
  $userId = UuidVo::create();
  $otherUserId = UuidVo::create();
  $anotherUserId = UuidVo::create();

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
    'id' => $otherUserId->value(),
    'name' => 'Other User',
    'email' => 'other@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $anotherUserId->value(),
    'name' => 'Another User',
    'email' => 'another@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  // 異なるユーザーIDの組み合わせでマッチングを追加
  DB::table('matchings')->insert([
    'id' => UuidVo::create()->value(),
    'user_id' => $userId->value(),
    'other_user_id' => $anotherUserId->value(),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $result = $matchingDomainService->isMatchingExists($userId, $otherUserId);

  expect($result)->toBeFalse();
});

test('逆方向の組み合わせでもfalseを返す（順序が重要）', function () {
  $matchingDomainService = new MatchingDomainService();
  $userId = UuidVo::create();
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
    'id' => $otherUserId->value(),
    'name' => 'Other User',
    'email' => 'other@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  // user_idとother_user_idを逆にしたマッチングを追加
  DB::table('matchings')->insert([
    'id' => UuidVo::create()->value(),
    'user_id' => $otherUserId->value(),
    'other_user_id' => $userId->value(),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  // 順序が違うのでfalseを返すべき
  $result = $matchingDomainService->isMatchingExists($userId, $otherUserId);

  expect($result)->toBeFalse();
});

