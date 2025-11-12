<?php

namespace App\UseCase;

use App\Domain\Models\vo\UuidVo;
use App\UseCase\QueryService\TalkQueryServiceInterface;
class TalkUseCase
{
    public function __construct(
        private readonly TalkQueryServiceInterface $talkQueryService
    ) {
    }

    public function getTalkRooms(string $userId): array
    {
        $userIdVo = UuidVo::NewUuidByVal($userId);
        if ($userIdVo === null) {
            throw new \Exception('Invalid user ID');
        }

        $talkRooms = $this->talkQueryService->getTalkRooms($userIdVo);
        if ($talkRooms === null) {
            return [];
        }
        // dd($talkRooms);

        return $talkRooms;
    }
}