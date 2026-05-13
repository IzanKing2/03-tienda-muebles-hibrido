<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ImagenController;

Route::prefix('v1')->group(function () {
    Route::get('/muebles', [ProductoController::class, 'index']);
    Route::get('/muebles/{id}', [ProductoController::class, 'show']);
    Route::get('/categorias', [CategoriaController::class, 'index']);
    Route::get('/categorias/{id}', [CategoriaController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/muebles', [ProductoController::class, 'store']);
        Route::put('/muebles/{id}', [ProductoController::class, 'update']);
        Route::delete('/muebles/{id}', [ProductoController::class, 'destroy']);
        
        Route::post('/categorias', [CategoriaController::class, 'store']);
        Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
        Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);
    });
});
