<?php

namespace App\Infra\Repository;

use App\Domain\Repository\LikeRepositoryInterface;
use App\Domain\Models\User\UserId;
use Illuminate\Support\Facades\DB;
use App\Domain\Models\Like\Like;

class LikeRepository implements LikeRepositoryInterface
{
    public function create(Like $like): void
    {
        DB::table('likes')->insert([
            'id' => $like->getLikeId()->value(),
            'user_id' => $like->getUserId()->value(),
            'target_user_id' => $like->getTargetUserId()->value(),
            'created_at' => now(),
        ]);
    }
}