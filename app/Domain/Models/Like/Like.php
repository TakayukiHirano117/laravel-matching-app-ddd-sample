<?php

namespace App\Domain\Models\Like;

use App\Domain\Models\vo\UuidVo;

class Like {
  private UuidVo $like_id;
  private UuidVo $user_id;
  private UuidVo $target_user_id;
  function __construct(UuidVo $like_id, UuidVo $user_id, UuidVo $target_user_id) {
    $this->like_id = $like_id;
    $this->user_id = $user_id;
    $this->target_user_id = $target_user_id;
  }

  public static function NewLikeByVal(UuidVo $like_id, UuidVo $user_id, UuidVo $target_user_id): self
  {
    return new self($like_id, $user_id, $target_user_id);
  }

  public function getLikeId(): UuidVo
  {
    return $this->like_id;
  }

  public function getUserId(): UuidVo
  {
    return $this->user_id;
  }

  public function getTargetUserId(): UuidVo
  {
    return $this->target_user_id;
  }
}