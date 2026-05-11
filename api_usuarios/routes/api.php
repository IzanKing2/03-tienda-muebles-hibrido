<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::prefix('v1')->group(function () {
    Route::post('/registrar', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

// Rutas protegidas
Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::get('/perfil', [AuthController::class, 'profile'])->middleware('ability:perfil.ver');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('ability:perfil.ver');
});
