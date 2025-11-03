<?php

namespace App\Infra\Repository;

use App\Domain\Repository\TransactionRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TransactionRepository implements TransactionRepositoryInterface
{
  /**
   * トランザクションを開始する
   */
  public function beginTransaction(): void
  {
    DB::beginTransaction();
  }

  /**
   * トランザクションをコミットする
   */
  public function commit(): void
  {
    DB::commit();
  }

  /**
   * トランザクションをロールバックする
   */
  public function rollBack(): void
  {
    DB::rollBack();
  }

  /**
   * クロージャを受け取ってトランザクション内で実行する
   * 例外が発生した場合は自動的にロールバックする
   *
   * @param callable $callback
   * @return mixed
   * @throws \Throwable
   */
  public function transaction(callable $callback)
  {
    return DB::transaction($callback);
  }
}

