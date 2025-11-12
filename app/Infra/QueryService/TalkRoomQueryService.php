<?php

namespace App\Infra\QueryService;

use App\UseCase\QueryService\TalkQueryServiceInterface;
use App\Domain\Models\vo\UuidVo;
use Illuminate\Support\Facades\DB;
use App\Domain\Models\TalkRoom\TalkRoom;
use App\Domain\Models\TalkRoom\LastMessage;
class TalkRoomQueryService implements TalkQueryServiceInterface
{
  public function getTalkRooms(UuidVo $userId): ?array
  {
    $talkRoomsFromDb = 
      DB::table('talk_rooms')
          ->where('user_id', $userId->value())
          ->orWhere('other_user_id', $userId->value())
          ->select('id', 'user_id', 'other_user_id', 'last_message')
          ->orderBy('updated_at', 'desc')
          ->get();

    if ($talkRoomsFromDb === null) {
      return null;
    }

    // dd($userId->value());
    // dd($talkRoomsFromDb);
    $talkRooms = [];
    foreach ($talkRoomsFromDb as $talkRoomFromDb) {
      $talkRooms[] = TalkRoom::NewTalkRoomByVal(
        UuidVo::NewUuidByVal($talkRoomFromDb->id),
        UuidVo::NewUuidByVal($talkRoomFromDb->user_id),
        UuidVo::NewUuidByVal($talkRoomFromDb->other_user_id),
        LastMessage::NewLastMessageByVal($talkRoomFromDb->last_message ?? '')
      );
    }
    // dd($talkRooms);
    return $talkRooms;
  }
}