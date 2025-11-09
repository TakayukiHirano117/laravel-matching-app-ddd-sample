<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UseCase\User\UserUseCase;

class UserController extends Controller
{
    public function __construct(
        private readonly UserUseCase $userUseCase
    ) {
    }

    public function getMyProfile(Request $request)
    {
        $userId = auth('sanctum')->id();
        if ($userId === null) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        $user = $this->userUseCase->getMyProfile($userId);
        return response()->json([
            'user_id' => $user->getUserId()->value(),
            'user_name' => $user->getUserName()->value(),
            'email' => $user->getEmail()->value(),
        ]);
    }
}
