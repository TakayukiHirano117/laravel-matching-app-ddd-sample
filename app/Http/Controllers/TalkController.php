<?php

namespace App\Http\Controllers;

use App\UseCase\Talk\TalkUseCase;
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
        return response()->json([
            'talk_rooms' => $talkRooms
        ]);
    }
}
