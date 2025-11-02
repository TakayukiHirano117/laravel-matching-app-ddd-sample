<?php

use App\Domain\Models\vo\Email;
use InvalidArgumentException;

test('有効なメールアドレスでインスタンスを生成できる', function () {
  $validEmail = 'test@example.com';
  $email = new Email($validEmail);

  expect($email->value())->toBe($validEmail);
});

test('空文字列でInvalidArgumentExceptionがスローされる', function () {
  expect(fn() => new Email(''))
    ->toThrow(InvalidArgumentException::class, 'メールアドレスは空にできません。');
});

test('空白のみの文字列でInvalidArgumentExceptionがスローされる', function () {
  expect(fn() => new Email('   '))
    ->toThrow(InvalidArgumentException::class, 'メールアドレスは空にできません。');
});

test('前後の空白がトリムされる', function () {
  $email = new Email('  test@example.com  ');

  expect($email->value())->toBe('test@example.com');
});

test('254文字を超えるメールアドレスでInvalidArgumentExceptionがスローされる', function () {
  $longEmail = str_repeat('a', 245) . '@example.com'; // 254文字を超える

  expect(fn() => new Email($longEmail))
    ->toThrow(InvalidArgumentException::class, 'メールアドレスは254文字以下である必要があります。');
});

test('長いメールアドレスも有効', function () {
  // 現実的な範囲での長いメールアドレス（filter_varで検証可能な範囲）
  $localPart = str_repeat('a', 60);
  $domainPart = 'example.com';
  $longEmail = $localPart . '@' . $domainPart; // 約73文字

  $email = new Email($longEmail);
  expect($email->value())->toBe($longEmail);
});

test('無効なメールアドレス形式でInvalidArgumentExceptionがスローされる', function () {
  expect(fn() => new Email('invalid-email'))
    ->toThrow(InvalidArgumentException::class, 'メールアドレスの形式が不正です: invalid-email');
});

test('@がないメールアドレスでInvalidArgumentExceptionがスローされる', function () {
  expect(fn() => new Email('testexample.com'))
    ->toThrow(InvalidArgumentException::class, 'メールアドレスの形式が不正です: testexample.com');
});

test('ドメイン部分がないメールアドレスでInvalidArgumentExceptionがスローされる', function () {
  expect(fn() => new Email('test@'))
    ->toThrow(InvalidArgumentException::class, 'メールアドレスの形式が不正です: test@');
});

test('NewEmailByValで既存のメールアドレスからインスタンスを生成できる', function () {
  $validEmail = 'test@example.com';
  $email = Email::NewEmailByVal($validEmail);

  expect($email->value())->toBe($validEmail);
});

test('valueで正しい値を取得できる', function () {
  $validEmail = 'test@example.com';
  $email = new Email($validEmail);

  expect($email->value())->toBe($validEmail);
});

test('equalsで同じメールアドレスの場合はtrueを返す', function () {
  $email1 = new Email('test@example.com');
  $email2 = new Email('test@example.com');

  expect($email1->equals($email2))->toBeTrue();
});

test('equalsで異なるメールアドレスの場合はfalseを返す', function () {
  $email1 = new Email('test1@example.com');
  $email2 = new Email('test2@example.com');

  expect($email1->equals($email2))->toBeFalse();
});

test('大文字小文字を区別して比較する', function () {
  $email1 = new Email('TEST@example.com');
  $email2 = new Email('test@example.com');

  expect($email1->equals($email2))->toBeFalse();
});

test('複雑なメールアドレス形式も有効', function () {
  $complexEmail = 'test.user+tag@example.co.jp';
  $email = new Email($complexEmail);

  expect($email->value())->toBe($complexEmail);
});

