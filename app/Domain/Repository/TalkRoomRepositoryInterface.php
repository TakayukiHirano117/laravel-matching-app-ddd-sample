<?php

namespace App\Domain\Repository;

use App\Domain\Models\TalkRoom\TalkRoom;

interface TalkRoomRepositoryInterface
{
  public function create(TalkRoom $talkRoom): void;
}