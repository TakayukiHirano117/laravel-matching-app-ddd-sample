<?php

use App\Domain\Models\vo\UuidVo;
use App\Infra\DomainService\LikeDomainService;
use App\Infra\DomainService\MatchingDomainService;
use App\Infra\Repository\LikeRepository;
use App\Infra\Repository\MatchingRepository;
use App\Infra\Repository\TransactionRepository;
use App\Infra\Repository\UserRepository;
use App\UseCase\Like\CreateLikeUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

test('executeでいいねが正しく作成される', function () {
  $likeRepository = new LikeRepository();
  $userRepository = new UserRepository();
  $likeDomainService = new LikeDomainService();
  $matchingDomainService = new MatchingDomainService();
  $matchingRepository = new MatchingRepository();
  $transactionRepository = new TransactionRepository();
  $createLikeUseCase = new CreateLikeUseCase(
    $likeRepository,
    $userRepository,
    $likeDomainService,
    $matchingDomainService,
    $matchingRepository,
    $transactionRepository
  );

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

  $input = [
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId->value(),
  ];

  $createLikeUseCase->execute($input);

  // データベースにいいねが保存されていることを確認
  $likeFromDb = DB::table('likes')
    ->where('user_id', $userId->value())
    ->where('target_user_id', $targetUserId->value())
    ->first();

  expect($likeFromDb)->not->toBeNull()
    ->and($likeFromDb->user_id)->toBe($userId->value())
    ->and($likeFromDb->target_user_id)->toBe($targetUserId->value());
});

test('executeで存在しないユーザーIDの場合に例外が発生する', function () {
  $likeRepository = new LikeRepository();
  $userRepository = new UserRepository();
  $likeDomainService = new LikeDomainService();
  $matchingDomainService = new MatchingDomainService();
  $matchingRepository = new MatchingRepository();
  $transactionRepository = new TransactionRepository();
  $createLikeUseCase = new CreateLikeUseCase(
    $likeRepository,
    $userRepository,
    $likeDomainService,
    $matchingDomainService,
    $matchingRepository,
    $transactionRepository
  );

  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  // ターゲットユーザーのみ作成
  DB::table('users')->insert([
    'id' => $targetUserId->value(),
    'name' => 'Target User',
    'email' => 'target@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $input = [
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId->value(),
  ];

  expect(fn() => $createLikeUseCase->execute($input))
    ->toThrow(Exception::class);
});

test('executeで存在しないターゲットユーザーIDの場合に例外が発生する', function () {
  $likeRepository = new LikeRepository();
  $userRepository = new UserRepository();
  $likeDomainService = new LikeDomainService();
  $matchingDomainService = new MatchingDomainService();
  $matchingRepository = new MatchingRepository();
  $transactionRepository = new TransactionRepository();
  $createLikeUseCase = new CreateLikeUseCase(
    $likeRepository,
    $userRepository,
    $likeDomainService,
    $matchingDomainService,
    $matchingRepository,
    $transactionRepository
  );

  $userId = UuidVo::create();
  $targetUserId = UuidVo::create();

  // ユーザーのみ作成
  DB::table('users')->insert([
    'id' => $userId->value(),
    'name' => 'Test User',
    'email' => 'test@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $input = [
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId->value(),
  ];

  expect(fn() => $createLikeUseCase->execute($input))
    ->toThrow(Exception::class);
});

test('executeで既にいいねが存在する場合に例外が発生する', function () {
  $likeRepository = new LikeRepository();
  $userRepository = new UserRepository();
  $likeDomainService = new LikeDomainService();
  $matchingDomainService = new MatchingDomainService();
  $matchingRepository = new MatchingRepository();
  $transactionRepository = new TransactionRepository();
  $createLikeUseCase = new CreateLikeUseCase(
    $likeRepository,
    $userRepository,
    $likeDomainService,
    $matchingDomainService,
    $matchingRepository,
    $transactionRepository
  );

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

  $input = [
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId->value(),
  ];

  expect(fn() => $createLikeUseCase->execute($input))
    ->toThrow(Exception::class);
});

test('executeで複数のいいねが正しく作成される', function () {
  $likeRepository = new LikeRepository();
  $userRepository = new UserRepository();
  $likeDomainService = new LikeDomainService();
  $matchingDomainService = new MatchingDomainService();
  $matchingRepository = new MatchingRepository();
  $transactionRepository = new TransactionRepository();
  $createLikeUseCase = new CreateLikeUseCase(
    $likeRepository,
    $userRepository,
    $likeDomainService,
    $matchingDomainService,
    $matchingRepository,
    $transactionRepository
  );

  $userId = UuidVo::create();
  $targetUserId1 = UuidVo::create();
  $targetUserId2 = UuidVo::create();

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
    'id' => $targetUserId1->value(),
    'name' => 'Target User 1',
    'email' => 'target1@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  DB::table('users')->insert([
    'id' => $targetUserId2->value(),
    'name' => 'Target User 2',
    'email' => 'target2@example.com',
    'password' => bcrypt('password'),
    'created_at' => now(),
    'updated_at' => now(),
  ]);

  $input1 = [
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId1->value(),
  ];

  $input2 = [
    'user_id' => $userId->value(),
    'target_user_id' => $targetUserId2->value(),
  ];

  $createLikeUseCase->execute($input1);
  $createLikeUseCase->execute($input2);

  // データベースに両方のいいねが保存されていることを確認
  $likesFromDb = DB::table('likes')
    ->where('user_id', $userId->value())
    ->get();

  expect($likesFromDb)->toHaveCount(2);
});

