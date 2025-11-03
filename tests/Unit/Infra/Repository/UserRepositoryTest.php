<?php

use App\Domain\Models\User\User;
use App\Domain\Models\User\UserName;
use App\Domain\Models\vo\Email;
use App\Domain\Models\vo\UuidVo;
use App\Infra\Repository\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('signUpでユーザーが正しくデータベースに保存され、トークンが返される', function () {
  $userRepository = new UserRepository();
  $userId = UuidVo::create();
  $userName = new UserName('Test User');
  $email = new Email('test@example.com');
  $user = new User($userId, $userName, $email);
  $password = 'password123';

  $token = $userRepository->signUp($user, $password);

  // トークンが返されることを確認
  expect($token)->toBeString()
    ->and($token)->not->toBeEmpty();

  // データベースにユーザーが保存されていることを確認
  $userFromDb = DB::table('users')->where('id', $userId->value())->first();
  expect($userFromDb)->not->toBeNull()
    ->and($userFromDb->name)->toBe('Test User')
    ->and($userFromDb->email)->toBe('test@example.com')
    ->and(Hash::check($password, $userFromDb->password))->toBeTrue();
});

test('findByIdで存在するユーザーIDで正しくユーザーを取得できる', function () {
  $userRepository = new UserRepository();
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

  $result = $userRepository->findById($userId);

  expect($result)->toBeInstanceOf(User::class)
    ->and($result->getUserId()->value())->toBe($userId->value())
    ->and($result->getUserName()->value())->toBe('Test User')
    ->and($result->getEmail()->value())->toBe('test@example.com');
});

test('findByIdで存在しないユーザーIDでnullを返す', function () {
  $userRepository = new UserRepository();
  $nonExistentUserId = UuidVo::create();

  $result = $userRepository->findById($nonExistentUserId);

  expect($result)->toBeNull();
});

test('findByIdで複数のユーザーが存在する場合でも正しく特定のユーザーを取得できる', function () {
  $userRepository = new UserRepository();
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

  $result = $userRepository->findById($userId2);

  expect($result)->toBeInstanceOf(User::class)
    ->and($result->getUserId()->value())->toBe($userId2->value())
    ->and($result->getUserName()->value())->toBe('User 2')
    ->and($result->getEmail()->value())->toBe('user2@example.com');
});

