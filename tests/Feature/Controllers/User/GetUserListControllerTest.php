<?php

use App\Domain\Models\vo\UuidVo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('GET /usersでユーザーリストが正しく取得できる', function () {
  $userId1 = UuidVo::create();
  $userId2 = UuidVo::create();

  // テスト用のユーザーを作成
  DB::table('users')->insert([
    'id' => $userId1->value(),
    'name' => 'User 1',
    'email' => 'user1@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $userId2->value(),
    'name' => 'User 2',
    'email' => 'user2@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  // 認証トークンを取得
  $user = \App\Models\User::find($userId1->value());
  Sanctum::actingAs($user);

  $response = $this->getJson('/api/users');

  $response->assertStatus(200)
    ->assertJsonStructure([
      'users' => [
        '*' => [
          'id',
          'name',
          'email',
        ],
      ],
    ])
    ->assertJsonCount(2, 'users');
});

test('GET /usersで認証されていない場合に401が返される', function () {
  $response = $this->getJson('/api/users');

  $response->assertStatus(401);
});

test('GET /usersで認証用ユーザーのみの場合、1件のユーザーが返される', function () {
  $userId = UuidVo::create();

  // テスト用のユーザーを作成（認証用）
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

  $response = $this->getJson('/api/users');

  $response->assertStatus(200)
    ->assertJsonCount(1, 'users')
    ->assertJson([
      'users' => [
        [
          'id' => $userId->value(),
          'name' => 'Test User',
          'email' => 'test@example.com',
        ],
      ],
    ]);
});

test('GET /usersで複数のユーザーが正しく取得できる', function () {
  $userId1 = UuidVo::create();
  $userId2 = UuidVo::create();
  $userId3 = UuidVo::create();

  // テスト用のユーザーを作成
  DB::table('users')->insert([
    'id' => $userId1->value(),
    'name' => 'User 1',
    'email' => 'user1@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $userId2->value(),
    'name' => 'User 2',
    'email' => 'user2@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $userId3->value(),
    'name' => 'User 3',
    'email' => 'user3@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $user = \App\Models\User::find($userId1->value());
  Sanctum::actingAs($user);

  $response = $this->getJson('/api/users');

  $response->assertStatus(200)
    ->assertJsonCount(3, 'users')
    ->assertJson([
      'users' => [
        [
          'id' => $userId1->value(),
          'name' => 'User 1',
          'email' => 'user1@example.com',
        ],
        [
          'id' => $userId2->value(),
          'name' => 'User 2',
          'email' => 'user2@example.com',
        ],
        [
          'id' => $userId3->value(),
          'name' => 'User 3',
          'email' => 'user3@example.com',
        ],
      ],
    ]);
});

test('GET /usersで各ユーザーのプロパティが正しく返される', function () {
  $userId = UuidVo::create();

  // テスト用のユーザーを作成
  DB::table('users')->insert([
    'id' => $userId->value(),
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $user = \App\Models\User::find($userId->value());
  Sanctum::actingAs($user);

  $response = $this->getJson('/api/users');

  $response->assertStatus(200)
    ->assertJson([
      'users' => [
        [
          'id' => $userId->value(),
          'name' => 'John Doe',
          'email' => 'john.doe@example.com',
        ],
      ],
    ]);
});

