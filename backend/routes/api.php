<?php

use App\Http\Controllers\SucursalController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::prefix('sucursales')->group(function () {
        Route::get('/', [SucursalController::class, 'index']);
        Route::get('/{id}', [SucursalController::class, 'show'])->whereNumber('id');
        Route::get('/{id}/empleados', [SucursalController::class, 'employees'])->whereNumber('id');
        Route::post('/', [SucursalController::class, 'store']);
        Route::put('/{id}', [SucursalController::class, 'update'])->whereNumber('id');
        Route::delete('/{id}', [SucursalController::class, 'destroy'])->whereNumber('id');
    });

    Route::prefix('empleados')->group(function () {
        Route::get('/', [EmpleadoController::class, 'index']);
        Route::post('/', [EmpleadoController::class, 'store']);
        Route::put('/{id}', [EmpleadoController::class, 'update'])->whereNumber('id');
        Route::delete('/{id}', [EmpleadoController::class, 'destroy'])->whereNumber('id');
    });
});
