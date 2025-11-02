<?php

use App\Domain\Models\User\UserName;
use InvalidArgumentException;

test('有効なユーザー名でインスタンスを生成できる', function () {
  $validName = 'テストユーザー';
  $userName = new UserName($validName);

  expect($userName->value())->toBe($validName);
});

test('最小長さ1文字のユーザー名でインスタンスを生成できる', function () {
  $userName = new UserName('A');

  expect($userName->value())->toBe('A');
});

test('最大長さ50文字のユーザー名でインスタンスを生成できる', function () {
  $longName = str_repeat('あ', 50); // 50文字
  $userName = new UserName($longName);

  expect($userName->value())->toBe($longName);
});

test('空文字列でInvalidArgumentExceptionがスローされる', function () {
  expect(fn() => new UserName(''))
    ->toThrow(InvalidArgumentException::class, 'ユーザー名は空にできません。');
});

test('空白のみの文字列でInvalidArgumentExceptionがスローされる', function () {
  expect(fn() => new UserName('   '))
    ->toThrow(InvalidArgumentException::class, 'ユーザー名は空にできません。');
});

test('前後の空白がトリムされる', function () {
  $userName = new UserName('  テストユーザー  ');

  expect($userName->value())->toBe('テストユーザー');
});

test('51文字を超えるユーザー名でInvalidArgumentExceptionがスローされる', function () {
  $longName = str_repeat('あ', 51); // 51文字

  expect(fn() => new UserName($longName))
    ->toThrow(InvalidArgumentException::class, 'ユーザー名は50文字以下である必要があります。実際: 51文字');
});

test('NewUserNameByValで既存のユーザー名からインスタンスを生成できる', function () {
  $validName = 'テストユーザー';
  $userName = UserName::NewUserNameByVal($validName);

  expect($userName->value())->toBe($validName);
});

test('valueで正しい値を取得できる', function () {
  $validName = 'テストユーザー';
  $userName = new UserName($validName);

  expect($userName->value())->toBe($validName);
});

test('equalsで同じユーザー名の場合はtrueを返す', function () {
  $userName1 = new UserName('テストユーザー');
  $userName2 = new UserName('テストユーザー');

  expect($userName1->equals($userName2))->toBeTrue();
});

test('equalsで異なるユーザー名の場合はfalseを返す', function () {
  $userName1 = new UserName('ユーザー1');
  $userName2 = new UserName('ユーザー2');

  expect($userName1->equals($userName2))->toBeFalse();
});

test('英数字のユーザー名も有効', function () {
  $userName = new UserName('user123');

  expect($userName->value())->toBe('user123');
});

test('特殊文字を含むユーザー名も有効', function () {
  $userName = new UserName('user_name-123');

  expect($userName->value())->toBe('user_name-123');
});

test('絵文字を含むユーザー名も有効', function () {
  $userName = new UserName('ユーザー😀');

  expect($userName->value())->toBe('ユーザー😀');
});

test('マルチバイト文字の長さが正しくカウントされる', function () {
  $userName = new UserName('あいうえお'); // 5文字

  expect(mb_strlen($userName->value()))->toBe(5);
});

test('トリム後も有効な範囲内であればインスタンスを生成できる', function () {
  $userName = new UserName('  A  '); // トリム後1文字

  expect($userName->value())->toBe('A');
});

test('トリム後に空になる場合は例外がスローされる', function () {
  expect(fn() => new UserName('   '))
    ->toThrow(InvalidArgumentException::class, 'ユーザー名は空にできません。');
});

