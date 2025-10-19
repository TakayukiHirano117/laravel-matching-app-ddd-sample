<?php

namespace App\Domain\Repository;

use App\Domain\Models\Like\Like;

interface LikeRepositoryInterface
{
    public function create(Like $like): void;
}