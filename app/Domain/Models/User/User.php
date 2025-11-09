<?php

namespace App\Domain\Models\User;

use App\Domain\Models\User\UserName;
use App\Domain\Models\vo\Email;
use App\Domain\Models\vo\UuidVo;

class User
{
  private UuidVo $user_id;
  private UserName $user_name;
  private Email $email;
  // profile情報は後で考える。
  public function __construct(UuidVo $user_id, UserName $user_name, Email $email)
  {
    // バリデーションかける
    $this->user_id = $user_id;
    $this->user_name = $user_name;
    $this->email = $email;
  }

  public static function NewUserByVal(UuidVo $user_id, UserName $user_name, Email $email): self
  {
    return new self($user_id, $user_name, $email);
  }

  // プロパティはprivateにしておく。
  // アクセスしていいプロパティに対してのみgetterを用意する。
  public function getUserId(): UuidVo
  {
    return $this->user_id;
  }

  public function getUserName(): UserName
  {
    return $this->user_name;
  }

  public function getEmail(): Email
  {
    return $this->email;
  }
}