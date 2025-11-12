<?php

namespace App\Http\Controllers;

use App\UseCase\TalkUseCase;
use App\Http\Resources\TalkRoomResource;
class TalkController extends Controller
{
    public function __construct(
        private readonly TalkUseCase $talkUseCase
    ) {
    }

    public function getTalkRooms()
    {
        $userId = auth('sanctum')->id();
        if ($userId === null) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $talkRooms = $this->talkUseCase->getTalkRooms($userId) ?? [];
        // dd($talkRooms);
        return response()->json([
            'talk_rooms' => TalkRoomResource::collection($talkRooms)
        ]);
    }
}
