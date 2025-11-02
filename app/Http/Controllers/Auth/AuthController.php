<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\UseCase\Auth\CreateUserUseCase;
use App\Http\Requests\Auth\SignUpRequest;
use App\Domain\Models\vo\UuidVo;
use App\Domain\Models\User\UserName;
use App\Domain\Models\vo\Email;
use App\Domain\Models\User\User;
use Ramsey\Uuid\Uuid;
use InvalidArgumentException;
class AuthController extends Controller
{

  public function __construct(
    private readonly CreateUserUseCase $createUserUseCase
  ) {
  }

public function signUp(SignUpRequest $request)
{
    try {
        $input = $request->all();
        $token = $this->createUserUseCase->execute($input);

        return response()->json([
            'message' => 'User created successfully',
            'token' => $token
        ], 201);
    } catch (InvalidArgumentException $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'User creation failed',
            'error' => $e->getMessage()
        ], 500);
    }
}

  public function signIn(Request $request)
  {
    
  }
}