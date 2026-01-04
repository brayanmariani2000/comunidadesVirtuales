<?php

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

// Todas estas rutas requieren rol de administrador
Route::middleware(['role:1'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');
    
    // Otras rutas de admin pueden ir aquí...
});