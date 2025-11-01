<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use App\UseCase\Auth\CreateUserUseCase;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\User\UserName;
use App\Domain\Models\vo\Email;
use App\Domain\Models\User\User;
use Ramsey\Uuid\Uuid;

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
        $userId = new UuidVo(Uuid::uuid4()->toString());
        $userName = new UserName($request->input('name'));
        $email = new Email($request->input('email'));
        $user = new User($userId, $userName, $email);

        // ユーザー登録ユースケースを呼び出す
        $this->createUserUseCase->execute($user, $request->input('password'));

        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }
}
