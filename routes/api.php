<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Like\CreateLikeController;
use App\Http\Controllers\User\getUserListController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok'
    ], 200);
})->name('health-check');

// TODO: prefixをつけること。
Route::post('/auth/sign-up', SignUpController::class)->name('auth.sign-up');
Route::post('/users/{user_id}/likes', CreateLikeController::class)->name('like.create');
Route::get('/users', getUserListController::class)->name('user.list');