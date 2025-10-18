<?php

namespace App\Domain\Models\User;

use App\Domain\Models\vo\UserId;

class User {
  private UserId $user_id;
  private UserName $user_name;
  private Email $email;
  // passwordは含めない
  // profile情報は後で考える。
  // created_at, updated_atは含めない
  function __construct(UserId $user_id, UserName $user_name, Email $email) {
    $this->user_id = $user_id;
    $this->user_name = $user_name;
    $this->email = $email;
  }

  public function getUserId(): UserId
  {
    return $this->user_id;
  }
}