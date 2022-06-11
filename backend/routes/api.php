<?php

use App\Http\Controllers\CounterpartyController;
use App\Http\Controllers\GoodController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OkvedController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TaskController;
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
    Route::get('', [CounterpartyController::class, 'index'])->middleware('role:admin|owner');
    Route::post('', [CounterpartyController::class, 'store']);

    Route::prefix('{counterparty}')->group(static function () {
      Route::get('', [CounterpartyController::class, 'getCounterparty']);
      Route::put('', [CounterpartyController::class, 'update']);
      Route::delete('', [CounterpartyController::class, 'destroy']);
    });
  });

  Route::prefix('goods')->group(static function () {
    // CRUD
    Route::get('', [GoodController::class, 'index'])->middleware('role:admin|owner');
    Route::post('', [GoodController::class, 'store']);

    Route::prefix('{good}')->group(static function () {
      Route::put('', [GoodController::class, 'update']);
      Route::delete('', [GoodController::class, 'destroy']);
    });
  });

  Route::prefix('services')->group(static function () {
    // CRUD
    Route::get('', [ServiceController::class, 'index'])->middleware('role:admin|owner');
    Route::post('', [ServiceController::class, 'store']);

    Route::prefix('{service}')->group(static function () {
      Route::put('', [ServiceController::class, 'update']);
      Route::delete('', [ServiceController::class, 'destroy']);
    });
  });

  Route::prefix('tasks')->middleware('role:admin')->group(static function () {
    // CRUD
    Route::get('', [TaskController::class, 'index']);

    Route::prefix('api')->group(static function () {
      Route::put('', [TaskController::class, 'update']);
    });
  });

  Route::prefix('okved')->middleware('role:admin|owner')->group(static function () {
    // CRUD
    Route::get('', [OkvedController::class, 'index']);
  });

  Route::post('/register', [AuthController::class, 'register'])->middleware('role:admin');
  Route::post('/logout', [AuthController::class, 'logout']);
});

Route::post('/login', [AuthController::class, 'login']);
