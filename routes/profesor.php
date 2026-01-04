<?php

use App\Http\Controllers\Profesor\ProfesorController;
use Illuminate\Support\Facades\Route;

// Todas estas rutas requieren rol de profesor
Route::middleware(['role:2'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [ProfesorController::class, 'dashboard'])
        ->name('dashboard');
    
    // Otras rutas de profesor pueden ir aquí...
});