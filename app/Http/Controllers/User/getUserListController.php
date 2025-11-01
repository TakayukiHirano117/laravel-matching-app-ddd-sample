<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\UseCase\User\GetUserListUseCase;
use Illuminate\Http\Request;

class getUserListController extends Controller
{
    /**
     * Handle the incoming request.
     */

    public function __construct(
        private readonly GetUserListUseCase $getUserListUseCase
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $users = $this->getUserListUseCase->execute();

        return response()->json([
            'users' => UserResource::collection($users)
        ]);
    }
}
