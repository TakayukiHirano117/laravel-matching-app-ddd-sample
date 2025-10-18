<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use App\UseCase\Auth\CreateUserUseCase;
use App\Domain\Models\User\UserId;
use App\Domain\Models\User\UserName;
use App\Domain\Models\vo\Email;
use App\Domain\Models\User\User;

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
        $userId = UserId::create();
        $userName = UserName::create($request->input('name'));
        $email = Email::create($request->input('email'));
        $user = new User($userId, $userName, $email);

        // ユーザー登録ユースケースを呼び出す
        $this->createUserUseCase->execute($user, $request->input('password'));

        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }
}
