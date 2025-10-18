<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use App\UseCase\Auth\CreateUserUseCase;

class SignUpController extends Controller
{
    /**
     * @param CreateUserUseCase $createUserUseCase
     */
    public function __construct(
        private readonly CreateUserUseCase $createUserUseCase
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(SignUpRequest $request)
    {
        // ドメインオブジェクト生成

        // ユーザー登録ユースケースを呼び出す
        $this->createUserUseCase->execute();

        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }
}
