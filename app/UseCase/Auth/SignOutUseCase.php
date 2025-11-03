<?php

namespace App\UseCase\Auth;

use Illuminate\Http\Request;
use Exception;

class SignOutUseCase
{
  public function __construct()
  {
  }

  /**
   * @param Request $request 認証済みリクエスト
   * @throws Exception
   */
  public function execute(Request $request): void
  {
    $user = $request->user();

    if ($user === null) {
      throw new Exception('User not authenticated');
    }

    $user->currentAccessToken()?->delete();
  }
}

