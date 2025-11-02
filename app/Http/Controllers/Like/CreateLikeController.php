<?php

namespace App\Http\Controllers\Like;

use App\Http\Controllers\Controller;
use App\Http\Requests\Like\CreateLikeRequest;
use App\UseCase\Like\CreateLikeUseCase;
use Exception;
class CreateLikeController extends Controller
{
    /**
     * @param CreateLikeUseCase $createLikeUseCase
     */
    public function __construct(
        private readonly CreateLikeUseCase $createLikeUseCase,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateLikeRequest $request)
    {
        try {
            $this->createLikeUseCase->execute($request->all());
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }

        return response()->json([
            'message' => 'Like created successfully'
        ], 201);
    }
}
