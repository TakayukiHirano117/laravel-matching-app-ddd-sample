<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\CreateLikeRequest;
use App\UseCase\Like\CreateLikeUseCase;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Models\vo\UuidVo;
class CreateLikeController extends Controller
{
    /**
     * @param CreateLikeUseCase $createLikeUseCase
     */
    public function __construct(
        private readonly CreateLikeUseCase $createLikeUseCase,
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateLikeRequest $request)
    {
        // ユーザーIdで検索を行い、２人とも存在していたらOK
        $user_id = UuidVo::NewUuidByVal($request->input('user_id'));
        $target_user_id = UuidVo::NewUuidByVal($request->input('target_user_id'));

        $user = $this->userRepository->findById($user_id);
        $target_user = $this->userRepository->findById($target_user_id);

        if ($user === null) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        if ($target_user === null) {
            return response()->json([
                'message' => 'Target user not found'
            ], 404);
        }

        // いいね作成のユースケースを呼び出す。
        $this->createLikeUseCase->execute($user_id, $target_user_id);

        return response()->json([
            'message' => 'Like created successfully'
        ], 201);
    }
}
