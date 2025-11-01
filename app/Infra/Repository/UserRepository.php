<?php

namespace App\Infra\Repository;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Models\User\User;
use App\Domain\Models\User\UserName;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\vo\Email;
class UserRepository implements UserRepositoryInterface
{
  /**
   * @param User $user
   * @param string $password
   */
  public function signUp(User $user, string $password): void
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

  public function findById(UuidVo $userId): ?User
  {
    $userFromDb = DB::table('users')
      ->where('id', $userId->value())
      ->first();

    if ($userFromDb === null) {
      return null;
    }

    return new User(
      UuidVo::NewUuidByVal($userFromDb->id),
      UserName::NewUserNameByVal($userFromDb->name),
      Email::NewEmailByVal($userFromDb->email)
    );
  }
}
