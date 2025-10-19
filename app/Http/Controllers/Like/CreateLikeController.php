<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\CreateLikeRequest;
use App\UseCase\Like\CreateLikeUseCase;
use App\Domain\Repository\UserRepositoryInterface;
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
        $user_id = $request->input('user_id');
        $target_user_id = $request->input('target_user_id');

        // $user = $this->userRepository->findById($user_id);
        // $target_user = $this->userRepository->findById($target_user_id);

        // いいね作成のユースケースを呼び出す。
        $this->createLikeUseCase->execute($request->input('user_id'), $request->input('target_user_id'));
    }
}
