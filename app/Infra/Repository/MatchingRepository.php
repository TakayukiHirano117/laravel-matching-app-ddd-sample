<?php

namespace App\Infra\Repository;

use App\Domain\Repository\MatchingRepositoryInterface;
use App\Domain\Models\Match\Matching;
use Illuminate\Support\Facades\DB;
class MatchingRepository implements MatchingRepositoryInterface
{
  public function create(Matching $matching): void
  {
    DB::table('matchings')->insert([
      'id' => $matching->getMatchingId()->value(),
      'user_id' => $matching->getUserId()->value(),
      'other_user_id' => $matching->getOtherUserId()->value(),
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }
}