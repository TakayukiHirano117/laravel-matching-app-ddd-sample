<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Like\CreateLikeController;
use App\Http\Controllers\User\getUserListController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TalkController;
Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok'
    ], 200);
})->name('health-check');

// TODO: prefixをつけること。
Route::post('/auth/sign-up', [AuthController::class, 'signUp'])->name('auth.sign-up');
Route::post('/auth/sign-in', [AuthController::class, 'signIn'])->name('auth.sign-in');
Route::post('/auth/sign-out', [AuthController::class, 'signOut'])->name('auth.sign-out')->middleware('auth:sanctum');

Route::post('/likes', CreateLikeController::class)->name('like.create')->middleware('auth:sanctum');
Route::get('/users', getUserListController::class)->name('user.list')->middleware('auth:sanctum');
Route::get('/user/me/info', [UserController::class, 'getMyProfile'])->name('user.me.info')->middleware('auth:sanctum');
Route::get('/talk/rooms', [TalkController::class, 'getTalkRooms'])->name('talk.rooms')->middleware('auth:sanctum');

Route::post('/messages', [MessageController::class, 'sendMessage'])->name('message.send')->middleware('auth:sanctum');