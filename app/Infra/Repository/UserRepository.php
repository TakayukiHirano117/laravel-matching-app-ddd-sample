<?php

namespace App\Infra\Repository;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Models\User\User;
use App\Domain\Models\User\UserName;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\vo\Email;
use App\Models\User as UserEloquent;
class UserRepository implements UserRepositoryInterface
{
  /**
   * @param User $user
   * @param string $password
   */
  public function signUp(User $user, string $password): string
  {
    $hashedPassword = Hash::make($password);
    UserEloquent::create([
      'id' => $user->getUserId()->value(),
      'name' => $user->getUserName()->value(),
      'email' => $user->getEmail()->value(),
      'password' => $hashedPassword,
      'created_at' => now(),
      'updated_at' => now(),
    ]);

    return $this->createToken($user);
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

  public function findByEmail(string $email): ?User
  {
    $userFromDb = DB::table('users')
      ->where('email', $email)
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

  public function verifyPassword(User $user, string $password): bool
  {
    $userEloquent = UserEloquent::find($user->getUserId()->value());

    if ($userEloquent === null) {
      return false;
    }

    return Hash::check($password, $userEloquent->password);
  }

  public function createToken(User $user, string $tokenName = 'api_token'): string
  {
    $userEloquent = UserEloquent::find($user->getUserId()->value());

    if ($userEloquent === null) {
      throw new \Exception('User not found');
    }

    return $userEloquent->createToken($tokenName)->plainTextToken;
  }
}
