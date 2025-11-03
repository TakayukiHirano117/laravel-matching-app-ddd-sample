<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Like\CreateLikeController;
use App\Http\Controllers\User\getUserListController;
use App\Http\Controllers\Auth\SignUpController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok'
    ], 200);
})->name('health-check');

// デバッグ用: トークン検証確認
Route::get('/debug/token', function (Request $request) {
    $token = $request->bearerToken();

    if (!$token) {
        return response()->json([
            'error' => 'No token provided',
            'header' => $request->header('Authorization')
        ]);
    }

    // トークンをパース（ID|hash形式）
    $parts = explode('|', $token, 2);
    $tokenId = $parts[0] ?? null;
    $tokenHash = $parts[1] ?? $token;

    // データベースからトークンを検索
    $dbToken = \DB::table('personal_access_tokens')
        ->where('id', $tokenId)
        ->first();

    return response()->json([
        'token_received' => $token,
        'token_id' => $tokenId,
        'token_hash_length' => strlen($tokenHash),
        'token_in_db' => $dbToken ? [
            'id' => $dbToken->id,
            'tokenable_id' => $dbToken->tokenable_id,
            'tokenable_type' => $dbToken->tokenable_type,
            'name' => $dbToken->name,
            'token_length' => strlen($dbToken->token),
            'expires_at' => $dbToken->expires_at,
        ] : 'Token not found in database',
        'user' => $request->user() ? [
            'id' => $request->user()->id,
            'name' => $request->user()->name,
        ] : 'User not authenticated'
    ]);
})->middleware('auth:sanctum');

// TODO: prefixをつけること。
Route::post('/auth/sign-up', [AuthController::class, 'signUp'])->name('auth.sign-up');
// Route::post('/auth/sign-up', SignUp::class)->name('auth.sign-up');
Route::post('/auth/sign-in', [AuthController::class, 'signIn'])->name('auth.sign-in');
// Route::post('/auth/sign-out', [AuthController::class, 'signOut'])->name('auth.sign-out')->middleware('auth:sanctum');

Route::post('/likes', CreateLikeController::class)->name('like.create')->middleware('auth:sanctum');
Route::get('/users', getUserListController::class)->name('user.list')->middleware('auth:sanctum');