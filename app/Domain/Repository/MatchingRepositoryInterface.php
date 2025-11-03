<?php

namespace App\Domain\Repository;

use App\Domain\Models\Match\Matching;

interface MatchingRepositoryInterface
{
  public function create(Matching $matching): void;
}