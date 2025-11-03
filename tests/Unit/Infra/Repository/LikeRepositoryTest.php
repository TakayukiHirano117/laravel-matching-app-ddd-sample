<?php

use App\Domain\Models\Like\Like;
use App\Domain\Models\vo\UuidVo;
use App\Infra\Repository\LikeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('createでLikeが正しくデータベースに保存される', function () {
  $likeRepository = new LikeRepository();
  $likeId = UuidVo::create();
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();
  $like = new Like($likeId, $userId, $targetUserId);

  // テスト用のユーザーを作成（外部キー制約がある場合に備えて）
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

  $likeRepository->create($like);

  // データベースにLikeが保存されていることを確認
  $likeFromDb = DB::table('likes')->where('id', $likeId->value())->first();
  expect($likeFromDb)->not->toBeNull()
    ->and($likeFromDb->user_id)->toBe($userId->value())
    ->and($likeFromDb->target_user_id)->toBe($targetUserId->value())
    ->and($likeFromDb->created_at)->not->toBeNull();
});

test('createで複数のLikeが正しく保存される', function () {
  $likeRepository = new LikeRepository();
  $userId = UuidVo::create();
  $targetUserId1 = UuidVo::create();
  $targetUserId2 = UuidVo::create();

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
    'id' => $targetUserId1->value(),
    'name' => 'Target User 1',
    'email' => 'target1@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $targetUserId2->value(),
    'name' => 'Target User 2',
    'email' => 'target2@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $likeId1 = UuidVo::create();
  $likeId2 = UuidVo::create();
  $like1 = new Like($likeId1, $userId, $targetUserId1);
  $like2 = new Like($likeId2, $userId, $targetUserId2);

  $likeRepository->create($like1);
  $likeRepository->create($like2);

  // データベースに両方のLikeが保存されていることを確認
  $likesFromDb = DB::table('likes')->where('user_id', $userId->value())->get();
  expect($likesFromDb)->toHaveCount(2);

  $like1FromDb = DB::table('likes')->where('id', $likeId1->value())->first();
  $like2FromDb = DB::table('likes')->where('id', $likeId2->value())->first();

  expect($like1FromDb)->not->toBeNull()
    ->and($like1FromDb->target_user_id)->toBe($targetUserId1->value())
    ->and($like2FromDb)->not->toBeNull()
    ->and($like2FromDb->target_user_id)->toBe($targetUserId2->value());
});

test('createでLikeの各プロパティが正しく保存される', function () {
  $likeRepository = new LikeRepository();
  $likeId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440000');
  $userId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440001');
  $targetUserId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440002');
  $like = new Like($likeId, $userId, $targetUserId);

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

  $likeRepository->create($like);

  // データベースにLikeが正しく保存されていることを確認
  $likeFromDb = DB::table('likes')->where('id', $likeId->value())->first();
  expect($likeFromDb->id)->toBe('550e8400-e29b-41d4-a716-446655440000')
    ->and($likeFromDb->user_id)->toBe('550e8400-e29b-41d4-a716-446655440001')
    ->and($likeFromDb->target_user_id)->toBe('550e8400-e29b-41d4-a716-446655440002');
});

