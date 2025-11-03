<?php

use App\Domain\Models\vo\UuidVo;
use App\Infra\QueryService\UserQueryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('ユーザーが0件の場合、空配列を返す', function () {
  $userQueryService = new UserQueryService();

  $result = $userQueryService->getUserList();

  expect($result)->toBeArray()
    ->and($result)->toBeEmpty();
});

test('ユーザーが1件の場合、そのユーザーを返す', function () {
  $userQueryService = new UserQueryService();
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

  $result = $userQueryService->getUserList();

  expect($result)->toBeArray()
    ->and($result)->toHaveCount(1)
    ->and($result[0])->toBeInstanceOf(\App\Domain\Models\User\User::class)
    ->and($result[0]->getUserId()->value())->toBe($userId->value())
    ->and($result[0]->getUserName()->value())->toBe('Test User')
    ->and($result[0]->getEmail()->value())->toBe('test@example.com');
});

test('ユーザーが複数件の場合、すべてのユーザーを返す', function () {
  $userQueryService = new UserQueryService();
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

  $result = $userQueryService->getUserList();

  expect($result)->toBeArray()
    ->and($result)->toHaveCount(3)
    ->and($result[0])->toBeInstanceOf(\App\Domain\Models\User\User::class)
    ->and($result[1])->toBeInstanceOf(\App\Domain\Models\User\User::class)
    ->and($result[2])->toBeInstanceOf(\App\Domain\Models\User\User::class)
    ->and($result[0]->getUserId()->value())->toBe($userId1->value())
    ->and($result[0]->getUserName()->value())->toBe('User 1')
    ->and($result[0]->getEmail()->value())->toBe('user1@example.com')
    ->and($result[1]->getUserId()->value())->toBe($userId2->value())
    ->and($result[1]->getUserName()->value())->toBe('User 2')
    ->and($result[1]->getEmail()->value())->toBe('user2@example.com')
    ->and($result[2]->getUserId()->value())->toBe($userId3->value())
    ->and($result[2]->getUserName()->value())->toBe('User 3')
    ->and($result[2]->getEmail()->value())->toBe('user3@example.com');
});

test('各ユーザーが正しくUserオブジェクトに変換されている', function () {
  $userQueryService = new UserQueryService();
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

  $result = $userQueryService->getUserList();

  expect($result)->toHaveCount(1)
    ->and($result[0]->getUserId())->toBeInstanceOf(UuidVo::class)
    ->and($result[0]->getUserName())->toBeInstanceOf(\App\Domain\Models\User\UserName::class)
    ->and($result[0]->getEmail())->toBeInstanceOf(\App\Domain\Models\vo\Email::class);
});

