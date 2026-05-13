<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::prefix('v1')->group(function () {
    Route::post('/registrar', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/perfil', [AuthController::class, 'perfil']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});
