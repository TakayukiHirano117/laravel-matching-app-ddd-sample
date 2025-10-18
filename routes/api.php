<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignUpController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok'
    ], 200);
})->name('health-check');
Route::post('/auth/sign-up', SignUpController::class)->name('auth.sign-up');