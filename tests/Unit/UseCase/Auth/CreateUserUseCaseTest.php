<?php

use App\Domain\Models\vo\UuidVo;
use App\Infra\DomainService\LikeDomainService;
use App\Infra\Repository\LikeRepository;
use App\Infra\Repository\UserRepository;
use App\UseCase\Auth\CreateUserUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('executeでユーザーが正しく作成され、トークンが返される', function () {
  $userRepository = new UserRepository();
  $createUserUseCase = new CreateUserUseCase($userRepository);

  $input = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => 'password123',
  ];

  $token = $createUserUseCase->execute($input);

  // トークンが返されることを確認
  expect($token)->toBeString()
    ->and($token)->not->toBeEmpty();

  // データベースにユーザーが保存されていることを確認
  $userFromDb = DB::table('users')
    ->where('email', 'test@example.com')
    ->first();

  expect($userFromDb)->not->toBeNull()
    ->and($userFromDb->name)->toBe('Test User')
    ->and($userFromDb->email)->toBe('test@example.com')
    ->and(Hash::check('password123', $userFromDb->password))->toBeTrue();
});

test('executeで複数のユーザーが正しく作成される', function () {
  $userRepository = new UserRepository();
  $createUserUseCase = new CreateUserUseCase($userRepository);

  $input1 = [
    'name' => 'User 1',
    'email' => 'user1@example.com',
    'password' => 'password123',
  ];

  $input2 = [
    'name' => 'User 2',
    'email' => 'user2@example.com',
    'password' => 'password456',
  ];

  $token1 = $createUserUseCase->execute($input1);
  $token2 = $createUserUseCase->execute($input2);

  // 両方のトークンが返されることを確認
  expect($token1)->toBeString()
    ->and($token2)->toBeString()
    ->and($token1)->not->toBe($token2);

  // データベースに両方のユーザーが保存されていることを確認
  $usersFromDb = DB::table('users')->get();
  expect($usersFromDb)->toHaveCount(2);
});

test('executeで異なる入力でユーザーが正しく作成される', function () {
  $userRepository = new UserRepository();
  $createUserUseCase = new CreateUserUseCase($userRepository);

  $input = [
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'password' => 'securePassword123',
  ];

  $token = $createUserUseCase->execute($input);

  expect($token)->toBeString()
    ->and($token)->not->toBeEmpty();

  $userFromDb = DB::table('users')
    ->where('email', 'john.doe@example.com')
    ->first();

  expect($userFromDb)->not->toBeNull()
    ->and($userFromDb->name)->toBe('John Doe')
    ->and(Hash::check('securePassword123', $userFromDb->password))->toBeTrue();
});

