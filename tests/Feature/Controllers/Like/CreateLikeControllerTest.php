<?php

use App\Domain\Models\vo\UuidVo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('POST /likesでいいねが正しく作成される', function () {
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

  // 認証トークンを取得
  $user = \App\Models\User::find($userId->value());
  Sanctum::actingAs($user);

  $response = $this->postJson('/api/likes', [
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId->value(),
  ]);

  $response->assertStatus(201)
    ->assertJsonStructure(['message']);

  // データベースにいいねが保存されていることを確認
  $likeFromDb = DB::table('likes')
    ->where('user_id', $userId->value())
    ->where('target_user_id', $targetUserId->value())
    ->first();

  expect($likeFromDb)->not->toBeNull();
});

test('POST /likesで認証されていない場合に401が返される', function () {
  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  $response = $this->postJson('/api/likes', [
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId->value(),
  ]);

  $response->assertStatus(401);
});

test('POST /likesでバリデーションエラーが返される（user_id未入力）', function () {
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

  $user = \App\Models\User::find($userId->value());
  Sanctum::actingAs($user);

  $response = $this->postJson('/api/likes', [
    'target_user_id' => $targetUserId->value(),
  ]);

  $response->assertStatus(422)
    ->assertJsonValidationErrors(['user_id']);
});

test('POST /likesでバリデーションエラーが返される（target_user_id未入力）', function () {
  $userId = UuidVo::create();

  // テスト用のユーザーを作成
  DB::table('users')->insert([
    'id' => $userId->value(),
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $user = \App\Models\User::find($userId->value());
  Sanctum::actingAs($user);

  $response = $this->postJson('/api/likes', [
    'user_id' => $userId->value(),
  ]);

  $response->assertStatus(422)
    ->assertJsonValidationErrors(['target_user_id']);
});

test('POST /likesで存在しないユーザーIDの場合に400エラーが返される', function () {
  $userId = UuidVo::create();
  $nonExistentUserId = UuidVo::create();

  // テスト用のユーザーを作成
  DB::table('users')->insert([
    'id' => $userId->value(),
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $user = \App\Models\User::find($userId->value());
  Sanctum::actingAs($user);

  $response = $this->postJson('/api/likes', [
    'user_id' => $userId->value(),
    'target_user_id' => $nonExistentUserId->value(),
  ]);

  $response->assertStatus(400)
    ->assertJsonStructure(['message']);
});

test('POST /likesで既にいいねが存在する場合に400エラーが返される', function () {
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

  // 既存のいいねを作成
  DB::table('likes')->insert([
    'id' => UuidVo::create()->value(),
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId->value(),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $user = \App\Models\User::find($userId->value());
  Sanctum::actingAs($user);

  $response = $this->postJson('/api/likes', [
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId->value(),
  ]);

  $response->assertStatus(400)
    ->assertJsonStructure(['message']);
});

test('POST /likesで無効なUUID形式の場合にバリデーションエラーが返される', function () {
  $userId = UuidVo::create();

  // テスト用のユーザーを作成
  DB::table('users')->insert([
    'id' => $userId->value(),
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $user = \App\Models\User::find($userId->value());
  Sanctum::actingAs($user);

  $response = $this->postJson('/api/likes', [
    'user_id' => 'invalid-uuid',
    'target_user_id' => UuidVo::create()->value(),
  ]);

  $response->assertStatus(422)
    ->assertJsonValidationErrors(['user_id']);
});

