<?php

namespace App\Domain\Models\TalkRoom;

use App\Domain\Models\vo\UuidVo;

class TalkRoom
{
  private UuidVo $talk_room_id;
  private UuidVo $user_id;
  private UuidVo $other_user_id;
  private LastMessage $last_message;

  public function __construct(UuidVo $talk_room_id, UuidVo $user_id, UuidVo $other_user_id, LastMessage $last_message)
  {
    $this->talk_room_id = $talk_room_id;
    $this->user_id = $user_id;
    $this->other_user_id = $other_user_id;
    $this->last_message = $last_message;
  }

  public static function NewTalkRoomByVal(UuidVo $talk_room_id, UuidVo $user_id, UuidVo $other_user_id, LastMessage $last_message): self
  {
    return new self($talk_room_id, $user_id, $other_user_id, $last_message);
  }

  public function getTalkRoomId(): UuidVo
  {
    return $this->talk_room_id;
  }

  public function getUserId(): UuidVo
  {
    return $this->user_id;
  }

  public function getOtherUserId(): UuidVo
  {
    return $this->other_user_id;
  }

  public function getLastMessage(): LastMessage
  {
    return $this->last_message;
  }
}