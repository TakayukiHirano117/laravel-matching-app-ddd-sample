<?php

namespace App\Infra\Repository;

use App\Domain\Repository\TalkRoomRepositoryInterface;
use App\Domain\Models\TalkRoom\TalkRoom;
use Illuminate\Support\Facades\DB;
class TalkRoomRepository implements TalkRoomRepositoryInterface
{
  public function create(TalkRoom $talkRoom): void
  {
    DB::table('talk_rooms')->insert([
      'id' => $talkRoom->getTalkRoomId()->value(),
      'user_id' => $talkRoom->getUserId()->value(),
      'other_user_id' => $talkRoom->getOtherUserId()->value(),
      'last_message' => $talkRoom->getLastMessage()->value(),
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }
}