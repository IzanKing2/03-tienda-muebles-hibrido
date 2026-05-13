<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MueblesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarritoController;
use App\Http\Controllers\AdminController;

// Página de inicio / catálogo público
Route::get('/', [MueblesController::class, 'index'])->name('home');
Route::get('/muebles', [MueblesController::class, 'index'])->name('muebles.index');
Route::get('/muebles/{id}', [MueblesController::class, 'show'])->name('muebles.show');

// Autenticación
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Perfil (requiere sesión)
Route::get('/perfil', [ProfileController::class, 'index'])->name('profile');

// ─── Carrito (requiere sesión) ────────────────────────────────────────────────
Route::get('/carrito', [CarritoController::class, 'index'])->name('carrito.index');
Route::post('/carrito/agregar', [CarritoController::class, 'agregar'])->name('carrito.agregar');
Route::patch('/carrito/{id}', [CarritoController::class, 'actualizar'])->name('carrito.actualizar');
Route::delete('/carrito/{id}', [CarritoController::class, 'eliminar'])->name('carrito.eliminar');
Route::delete('/carrito', [CarritoController::class, 'vaciar'])->name('carrito.vaciar');

// ─── Panel de Administración ──────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Muebles CRUD
    Route::get('/muebles', [AdminController::class, 'muebleIndex'])->name('muebles.index');
    Route::get('/muebles/crear', [AdminController::class, 'muebleCreate'])->name('muebles.create');
    Route::post('/muebles', [AdminController::class, 'muebleStore'])->name('muebles.store');
    Route::get('/muebles/{id}/editar', [AdminController::class, 'muebleEdit'])->name('muebles.edit');
    Route::put('/muebles/{id}', [AdminController::class, 'muebleUpdate'])->name('muebles.update');
    Route::delete('/muebles/{id}', [AdminController::class, 'muebleDestroy'])->name('muebles.destroy');

    // Usuarios CRUD
    Route::get('/usuarios', [AdminController::class, 'usuarioIndex'])->name('usuarios.index');
    Route::get('/usuarios/crear', [AdminController::class, 'usuarioCreate'])->name('usuarios.create');
    Route::post('/usuarios', [AdminController::class, 'usuarioStore'])->name('usuarios.store');
    Route::get('/usuarios/{id}/editar', [AdminController::class, 'usuarioEdit'])->name('usuarios.edit');
    Route::put('/usuarios/{id}', [AdminController::class, 'usuarioUpdate'])->name('usuarios.update');
    Route::delete('/usuarios/{id}', [AdminController::class, 'usuarioDestroy'])->name('usuarios.destroy');
});
