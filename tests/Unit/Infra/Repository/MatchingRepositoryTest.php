<?php

use App\Domain\Models\Match\Matching;
use App\Domain\Models\vo\UuidVo;
use App\Infra\Repository\MatchingRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('createでMatchingが正しくデータベースに保存される', function () {
  $matchingRepository = new MatchingRepository();
  $matchingId = UuidVo::create();
  $userId = UuidVo::create();
  $otherUserId = UuidVo::create();
  $matching = new Matching($matchingId, $userId, $otherUserId);

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
    'id' => $otherUserId->value(),
    'name' => 'Other User',
    'email' => 'other@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $matchingRepository->create($matching);

  // データベースにMatchingが保存されていることを確認
  $matchingFromDb = DB::table('matchings')->where('id', $matchingId->value())->first();
  expect($matchingFromDb)->not->toBeNull()
    ->and($matchingFromDb->user_id)->toBe($userId->value())
    ->and($matchingFromDb->other_user_id)->toBe($otherUserId->value())
    ->and($matchingFromDb->created_at)->not->toBeNull();
});

test('createで複数のMatchingが正しく保存される', function () {
  $matchingRepository = new MatchingRepository();
  $userId = UuidVo::create();
  $otherUserId1 = UuidVo::create();
  $otherUserId2 = UuidVo::create();

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
    'id' => $otherUserId1->value(),
    'name' => 'Other User 1',
    'email' => 'other1@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $otherUserId2->value(),
    'name' => 'Other User 2',
    'email' => 'other2@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $matchingId1 = UuidVo::create();
  $matchingId2 = UuidVo::create();
  $matching1 = new Matching($matchingId1, $userId, $otherUserId1);
  $matching2 = new Matching($matchingId2, $userId, $otherUserId2);

  $matchingRepository->create($matching1);
  $matchingRepository->create($matching2);

  // データベースに両方のMatchingが保存されていることを確認
  $matchingsFromDb = DB::table('matchings')->where('user_id', $userId->value())->get();
  expect($matchingsFromDb)->toHaveCount(2);

  $matching1FromDb = DB::table('matchings')->where('id', $matchingId1->value())->first();
  $matching2FromDb = DB::table('matchings')->where('id', $matchingId2->value())->first();

  expect($matching1FromDb)->not->toBeNull()
    ->and($matching1FromDb->other_user_id)->toBe($otherUserId1->value())
    ->and($matching2FromDb)->not->toBeNull()
    ->and($matching2FromDb->other_user_id)->toBe($otherUserId2->value());
});

test('createでMatchingの各プロパティが正しく保存される', function () {
  $matchingRepository = new MatchingRepository();
  $matchingId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440000');
  $userId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440001');
  $otherUserId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440002');
  $matching = new Matching($matchingId, $userId, $otherUserId);

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

  $matchingRepository->create($matching);

  // データベースにMatchingが正しく保存されていることを確認
  $matchingFromDb = DB::table('matchings')->where('id', $matchingId->value())->first();
  expect($matchingFromDb->id)->toBe('550e8400-e29b-41d4-a716-446655440000')
    ->and($matchingFromDb->user_id)->toBe('550e8400-e29b-41d4-a716-446655440001')
    ->and($matchingFromDb->other_user_id)->toBe('550e8400-e29b-41d4-a716-446655440002');
});

