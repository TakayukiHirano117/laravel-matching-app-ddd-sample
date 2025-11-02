<?php

use App\Domain\Models\vo\UuidVo;
use InvalidArgumentException;

test('有効なUUIDでインスタンスを生成できる', function () {
  $validUuid = '550e8400-e29b-41d4-a716-446655440000';
  $uuidVo = new UuidVo($validUuid);

  expect($uuidVo->value())->toBe($validUuid);
});

test('無効なUUIDでInvalidArgumentExceptionがスローされる', function () {
  $invalidUuid = 'invalid-uuid';

  expect(fn() => new UuidVo($invalidUuid))
    ->toThrow(InvalidArgumentException::class, 'Invalid UUID format: invalid-uuid');
});

test('NewUuidByValで既存のUUIDからインスタンスを生成できる', function () {
  $validUuid = '550e8400-e29b-41d4-a716-446655440000';
  $uuidVo = UuidVo::NewUuidByVal($validUuid);

  expect($uuidVo->value())->toBe($validUuid);
});

test('createで新しい有効なUUIDを生成できる', function () {
  $uuidVo = UuidVo::create();

  expect($uuidVo->value())->toBeString()
    ->and(\Ramsey\Uuid\Uuid::isValid($uuidVo->value()))->toBeTrue();
});

test('createで生成されるUUIDは毎回異なる', function () {
  $uuidVo1 = UuidVo::create();
  $uuidVo2 = UuidVo::create();

  expect($uuidVo1->value())->not->toBe($uuidVo2->value());
});

test('valueで正しい値を取得できる', function () {
  $validUuid = '550e8400-e29b-41d4-a716-446655440000';
  $uuidVo = new UuidVo($validUuid);

  expect($uuidVo->value())->toBe($validUuid);
});

test('equalsで同じUUIDの場合はtrueを返す', function () {
  $validUuid = '550e8400-e29b-41d4-a716-446655440000';
  $uuidVo1 = new UuidVo($validUuid);
  $uuidVo2 = new UuidVo($validUuid);

  expect($uuidVo1->equals($uuidVo2))->toBeTrue();
});

test('equalsで異なるUUIDの場合はfalseを返す', function () {
  $uuidVo1 = new UuidVo('550e8400-e29b-41d4-a716-446655440000');
  $uuidVo2 = new UuidVo('550e8400-e29b-41d4-a716-446655440001');

  expect($uuidVo1->equals($uuidVo2))->toBeFalse();
});

test('空文字列でInvalidArgumentExceptionがスローされる', function () {
  expect(fn() => new UuidVo(''))
    ->toThrow(InvalidArgumentException::class, 'Invalid UUID format: ');
});

test('数字のみの文字列でInvalidArgumentExceptionがスローされる', function () {
  expect(fn() => new UuidVo('123456789'))
    ->toThrow(InvalidArgumentException::class, 'Invalid UUID format: 123456789');
});

