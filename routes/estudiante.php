<?php

use App\Http\Controllers\Estudiante\EstudianteController;
use Illuminate\Support\Facades\Route;

// Todas estas rutas requieren rol de estudiante
Route::middleware(['role:3'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [EstudianteController::class, 'dashboard'])
        ->name('dashboard');
    
    // Otras rutas de estudiante pueden ir aquí...
});