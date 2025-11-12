<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Domain\Models\TalkRoom\TalkRoom;
class TalkRoomResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, string>
   */
  public function toArray(Request $request): array
  {
    /** @var TalkRoom $talkRoom */
    $talkRoom = $this->resource;
    return [
      'id' => $talkRoom->getTalkRoomId()->value(),
      'user_id' => $talkRoom->getUserId()->value(),
      'other_user_id' => $talkRoom->getOtherUserId()->value(),
      'last_message' => $talkRoom->getLastMessage()->value(),
    ];
  }
}