<?php

namespace App\Infra\QueryService;

use Illuminate\Support\Facades\DB;
use App\UseCase\QueryService\UserQueryServiceInterface;
use App\Domain\Models\User\User;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\User\UserName;
use App\Domain\Models\vo\Email;
class UserQueryService implements UserQueryServiceInterface
{
  public function __construct(
  ) {
  }

  public function getUserList(): array
  {
    // Usersにdeleted_atをつける
    $usersFromDb = DB::table('users')->get();
    $users = [];

    foreach ($usersFromDb as $userFromDb) {
      $users[] = User::NewUserByVal(
        UuidVo::NewUuidByVal($userFromDb->id),
        UserName::NewUserNameByVal($userFromDb->name),
        Email::NewEmailByVal($userFromDb->email)
      );
    }

    return $users;
  }
}