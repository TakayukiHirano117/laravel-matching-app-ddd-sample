<?php

namespace App\Domain\Repository;

interface TransactionRepositoryInterface
{
  /**
   * トランザクションを開始する
   */
  public function beginTransaction(): void;

  /**
   * トランザクションをコミットする
   */
  public function commit(): void;

  /**
   * トランザクションをロールバックする
   */
  public function rollBack(): void;

  /**
   * クロージャを受け取ってトランザクション内で実行する
   * 例外が発生した場合は自動的にロールバックする
   *
   * @param callable $callback
   * @return mixed
   * @throws \Throwable
   */
  public function transaction(callable $callback);
}

