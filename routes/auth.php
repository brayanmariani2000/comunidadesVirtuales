<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    // Mostrar formulario de login
    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    // Procesar login
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    // Cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});