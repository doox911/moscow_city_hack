<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->group(function () {
  Route::prefix('user')->group(static function () {
    Route::get('/', [AuthController::class, 'getUser']);
    Route::put('/', [AuthController::class, 'updateUser']);
  });
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
