<?php

use App\Domain\Models\vo\UuidVo;
use App\Infra\QueryService\UserQueryService;
use App\UseCase\User\GetUserListUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('executeでユーザーリストが正しく取得できる', function () {
  $userQueryService = new UserQueryService();
  $getUserListUseCase = new GetUserListUseCase($userQueryService);

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

  $result = $getUserListUseCase->execute();

  expect($result)->toBeArray()
    ->and($result)->toHaveCount(2)
    ->and($result[0])->toBeInstanceOf(\App\Domain\Models\User\User::class)
    ->and($result[1])->toBeInstanceOf(\App\Domain\Models\User\User::class);
});

test('executeでユーザーが0件の場合、空配列を返す', function () {
  $userQueryService = new UserQueryService();
  $getUserListUseCase = new GetUserListUseCase($userQueryService);

  $result = $getUserListUseCase->execute();

  expect($result)->toBeArray()
    ->and($result)->toBeEmpty();
});

test('executeで複数のユーザーが正しく取得できる', function () {
  $userQueryService = new UserQueryService();
  $getUserListUseCase = new GetUserListUseCase($userQueryService);

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

  $result = $getUserListUseCase->execute();

  expect($result)->toBeArray()
    ->and($result)->toHaveCount(3)
    ->and($result[0])->toBeInstanceOf(\App\Domain\Models\User\User::class)
    ->and($result[1])->toBeInstanceOf(\App\Domain\Models\User\User::class)
    ->and($result[2])->toBeInstanceOf(\App\Domain\Models\User\User::class)
    ->and($result[0]->getUserId()->value())->toBe($userId1->value())
    ->and($result[1]->getUserId()->value())->toBe($userId2->value())
    ->and($result[2]->getUserId()->value())->toBe($userId3->value());
});

test('executeで各ユーザーのプロパティが正しく取得できる', function () {
  $userQueryService = new UserQueryService();
  $getUserListUseCase = new GetUserListUseCase($userQueryService);

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

  $result = $getUserListUseCase->execute();

  expect($result)->toHaveCount(1)
    ->and($result[0]->getUserId()->value())->toBe($userId->value())
    ->and($result[0]->getUserName()->value())->toBe('John Doe')
    ->and($result[0]->getEmail()->value())->toBe('john.doe@example.com');
});

