<?php

namespace App\Infra\Repository;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Models\User\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Domain\Models\User\UserId;
class UserRepository implements UserRepositoryInterface
{
  /**
   * @param User $user
   * @param string $password
   */
  public function create(User $user, string $password): void
  {
    $hashedPassword = Hash::make($password);

    DB::table('users')->insert([
      'id' => $user->getUserId()->value(),
      'name' => $user->getUserName()->value(),
      'email' => $user->getEmail()->value(),
      'password' => $hashedPassword,
      'created_at' => now(),
      'updated_at' => now(),
    ]);
  }

  public function findById(UserId $userId): ?User
  {
    $user = DB::table('users')->where('id', $userId->value())->first();
    if (!$user) {
      return null;
    }
    return $user;
  }
}
