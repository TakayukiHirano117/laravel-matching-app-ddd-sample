<?php

namespace App\Domain\Models\Match;

use App\Domain\Models\vo\UuidVo;

class Matching {
  private UuidVo $matching_id;
  private UuidVo $user_id;
  private UuidVo $other_user_id;

  function __construct(UuidVo $matching_id, UuidVo $user_id, UuidVo $other_user_id) {
    $this->matching_id = $matching_id;
    $this->user_id = $user_id;
    $this->other_user_id = $other_user_id;
  }

  public static function NewMatchingByVal(UuidVo $matching_id, UuidVo $user_id, UuidVo $other_user_id): self
  {
    return new self($matching_id, $user_id, $other_user_id);
  }

  public function getMatchingId(): UuidVo
  {
    return $this->matching_id;
  }

  public function getUserId(): UuidVo
  {
    return $this->user_id;
  }

  public function getOtherUserId(): UuidVo
  {
    return $this->other_user_id;
  }
}