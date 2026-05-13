<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MueblesController;
use App\Http\Controllers\ProfileController;

Route::get('/', [MueblesController::class, 'index'])->name('home');

Route::get('/muebles', [MueblesController::class, 'index'])->name('muebles.index');
Route::get('/muebles/{id}', [MueblesController::class, 'show'])->name('muebles.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/registro', [AuthController::class, 'showRegister'])->name('register');
Route::post('/registro', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/perfil', [ProfileController::class, 'index'])->name('profile');
