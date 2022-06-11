<?php

use App\Http\Controllers\CounterpartyController;
use App\Http\Controllers\MenuController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->group(function () {
  Route::prefix('user')->group(static function () {
    Route::get('/all', [AuthController::class, 'getAll'])->middleware('role:admin|government');
    Route::get('/', [AuthController::class, 'getUser']);
    Route::put('/', [AuthController::class, 'updateUser'])->middleware('role:admin');
  });

  Route::prefix('menu')->group(static function () {
    Route::get('/', [MenuController::class, 'index']);
  });

  Route::prefix('counterparties')->group(static function () {
    // CRUD
    Route::get('', [CounterpartyController::class, 'index']);
    Route::post('', [CounterpartyController::class, 'store']);

    Route::prefix('{counterparty}')->group(static function () {
      Route::put('', [CounterpartyController::class, 'update']);
      Route::delete('', [CounterpartyController::class, 'destroy']);
    });
  });

  Route::post('/register', [AuthController::class, 'register'])->middleware('role:admin');
  Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/login', [AuthController::class, 'login']);
