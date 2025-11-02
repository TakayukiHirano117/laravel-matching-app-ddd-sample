<?php

use App\Domain\Models\User\User;
use App\Domain\Models\User\UserName;
use App\Domain\Models\vo\Email;
use App\Domain\Models\vo\UuidVo;

test('Userインスタンスを生成できる', function () {
  $userId = UuidVo::create();
  $userName = new UserName('テストユーザー');
  $email = new Email('test@example.com');

  $user = new User($userId, $userName, $email);

  expect($user)->toBeInstanceOf(User::class);
});

test('getUserIdで正しいユーザーIDを取得できる', function () {
  $userId = UuidVo::create();
  $userName = new UserName('テストユーザー');
  $email = new Email('test@example.com');

  $user = new User($userId, $userName, $email);

  expect($user->getUserId())->toBe($userId)
    ->and($user->getUserId()->value())->toBe($userId->value());
});

test('getUserNameで正しいユーザー名を取得できる', function () {
  $userId = UuidVo::create();
  $userName = new UserName('テストユーザー');
  $email = new Email('test@example.com');

  $user = new User($userId, $userName, $email);

  expect($user->getUserName())->toBe($userName)
    ->and($user->getUserName()->value())->toBe('テストユーザー');
});

test('getEmailで正しいメールアドレスを取得できる', function () {
  $userId = UuidVo::create();
  $userName = new UserName('テストユーザー');
  $email = new Email('test@example.com');

  $user = new User($userId, $userName, $email);

  expect($user->getEmail())->toBe($email)
    ->and($user->getEmail()->value())->toBe('test@example.com');
});

test('NewUserByValでインスタンスを生成できる', function () {
  $userId = UuidVo::create();
  $userName = new UserName('テストユーザー');
  $email = new Email('test@example.com');

  $user = User::NewUserByVal($userId, $userName, $email);

  expect($user)->toBeInstanceOf(User::class)
    ->and($user->getUserId())->toBe($userId)
    ->and($user->getUserName())->toBe($userName)
    ->and($user->getEmail())->toBe($email);
});

test('異なるユーザーIDを持つユーザーは異なるユーザーとして扱われる', function () {
  $userName = new UserName('テストユーザー');
  $email = new Email('test@example.com');

  $user1 = new User(UuidVo::create(), $userName, $email);
  $user2 = new User(UuidVo::create(), $userName, $email);

  expect($user1->getUserId()->equals($user2->getUserId()))->toBeFalse();
});

test('同じユーザーIDを持つユーザーは同じIDを持つ', function () {
  $userId = UuidVo::create();
  $userName1 = new UserName('ユーザー1');
  $userName2 = new UserName('ユーザー2');
  $email1 = new Email('test1@example.com');
  $email2 = new Email('test2@example.com');

  $user1 = new User($userId, $userName1, $email1);
  $user2 = new User($userId, $userName2, $email2);

  expect($user1->getUserId()->equals($user2->getUserId()))->toBeTrue();
});

test('複数のプロパティが正しく設定される', function () {
  $userId = UuidVo::NewUuidByVal('550e8400-e29b-41d4-a716-446655440000');
  $userName = new UserName('John Doe');
  $email = new Email('john.doe@example.com');

  $user = new User($userId, $userName, $email);

  expect($user->getUserId()->value())->toBe('550e8400-e29b-41d4-a716-446655440000')
    ->and($user->getUserName()->value())->toBe('John Doe')
    ->and($user->getEmail()->value())->toBe('john.doe@example.com');
});

