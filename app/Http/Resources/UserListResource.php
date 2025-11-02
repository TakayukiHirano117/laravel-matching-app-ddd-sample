<?php

namespace App\Http\Resources;

use App\Domain\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserListResource extends JsonResource
{
  /**
   * Transform the resource into an array.
   *
   * @return array<string, string>
   */
  public function toArray(Request $request): array
  {
    /** @var User $user */
    $user = $this->resource;

    return [
      'id' => $user->getUserId()->value(),
      'name' => $user->getUserName()->value(),
      'email' => $user->getEmail()->value(),
    ];
  }
}

