<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfesorController;
use App\Http\Controllers\EstudianteController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Rutas de autenticación
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    // Home principal según rol
    Route::get('/home', [HomeController::class, 'home'])->name('home');
    
    // Rutas de administrador
    Route::prefix('admin')->name('admin.')->middleware('role:1')->group(function () {
        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'tablero'])->name('dashboard');
        
        // Gestión de Usuarios
        Route::get('/usuarios', [App\Http\Controllers\AdminController::class, 'usuarios'])->name('usuarios');
        Route::get('/usuarios/crear', [App\Http\Controllers\AdminController::class, 'crearUsuario'])->name('usuarios.crear');
        Route::post('/usuarios', [App\Http\Controllers\AdminController::class, 'guardarUsuario'])->name('usuarios.guardar');
        Route::get('/usuarios/{id}/editar', [App\Http\Controllers\AdminController::class, 'editarUsuario'])->name('usuarios.editar');
        Route::put('/usuarios/{id}', [App\Http\Controllers\AdminController::class, 'actualizarUsuario'])->name('usuarios.actualizar');
        Route::delete('/usuarios/{id}', [App\Http\Controllers\AdminController::class, 'eliminarUsuario'])->name('usuarios.eliminar');
        
        // Gestión de Unidades Curriculares
        Route::get('/unidades', [App\Http\Controllers\AdminController::class, 'unidadesCurriculares'])->name('unidades');
        Route::get('/unidades/crear', [App\Http\Controllers\AdminController::class, 'crearUnidad'])->name('unidades.crear');
        Route::post('/unidades', [App\Http\Controllers\AdminController::class, 'guardarUnidad'])->name('unidades.guardar');
        Route::get('/unidades/{id}/editar', [App\Http\Controllers\AdminController::class, 'editarUnidad'])->name('unidades.editar');
        Route::put('/unidades/{id}', [App\Http\Controllers\AdminController::class, 'actualizarUnidad'])->name('unidades.actualizar');
        Route::delete('/unidades/{id}', [App\Http\Controllers\AdminController::class, 'eliminarUnidad'])->name('unidades.eliminar');
        
        // Gestión de Asignaciones e Inscripciones
        Route::get('/asignaciones', [App\Http\Controllers\AdminController::class, 'asignaciones'])->name('asignaciones');
        Route::post('/asignaciones', [App\Http\Controllers\AdminController::class, 'guardarAsignacion'])->name('asignaciones.guardar');
        Route::get('/inscripciones', [App\Http\Controllers\AdminController::class, 'inscripciones'])->name('inscripciones');
        Route::post('/inscripciones', [App\Http\Controllers\AdminController::class, 'guardarInscripcion'])->name('inscripciones.guardar');
        
        // Reportes
        Route::get('/reportes', [App\Http\Controllers\AdminController::class, 'reportes'])->name('reportes');
    });
    
    // Rutas de profesor
    Route::prefix('profesor')->name('profesor.')->middleware('role:2')->group(function () {
        Route::get('/dashboard', [ProfesorController::class, 'dashboard'])->name('dashboard');
        Route::get('/unidades', [ProfesorController::class, 'unidades'])->name('unidades');
        Route::get('/unidades/crear-lote', [ProfesorController::class, 'createUnidadLote'])->name('unidades.create.lote');
        Route::post('/unidades/crear-lote', [ProfesorController::class, 'storeUnidadLote'])->name('unidades.store.lote');
        Route::get('/materiales', [ProfesorController::class, 'materialesIndex'])->name('materiales');
        Route::get('/trabajos', [ProfesorController::class, 'trabajos'])->name('trabajos');
        Route::get('/trabajos/{id}/calificar', [ProfesorController::class, 'calificarTrabajo'])->name('trabajos.calificar');
        Route::post('/trabajos/{id}/calificar', [ProfesorController::class, 'calificarTrabajoStore'])->name('trabajos.calificar.store');
        Route::get('/trabajos/{id}/descargar', [ProfesorController::class, 'descargarTrabajo'])->name('trabajos.descargar');
        Route::delete('/unidades/{id}', [ProfesorController::class, 'destroyUnidad'])->name('unidades.destroy');
    });
    
    // Rutas de estudiante
    Route::prefix('estudiante')->name('estudiante.')->middleware('role:3')->group(function () {
        Route::get('/dashboard', [EstudianteController::class, 'dashboard'])->name('dashboard');
        Route::get('/unidades', [EstudianteController::class, 'unidades'])->name('unidades');
        Route::get('/trabajos', [EstudianteController::class, 'trabajos'])->name('trabajos');

        // Route::get('/chat-unidades', [EstudianteController::class, 'chatUnidades'])->name('chat.unidades'); // Eliminado por redundancia/vista faltante
        Route::get('/materiales', [EstudianteController::class, 'materiales'])->name('materiales');
        Route::get('/trabajos/entregar', [EstudianteController::class, 'entregarTrabajo'])->name('trabajos.entregar');
        Route::post('/trabajos/entregar', [EstudianteController::class, 'entregarTrabajoStore'])->name('trabajos.entregar.store');
        Route::get('/trabajos/entregas', [EstudianteController::class, 'misEntregas'])->name('trabajos.entregas');
        Route::get('/trabajos/calificaciones', [EstudianteController::class, 'calificacionesTrabajos'])->name('trabajos.calificaciones');
    });
    
    // Rutas de chat (comunes para todos los roles)
    Route::prefix('chat')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::get('/crear', [ChatController::class, 'create'])->name('create');
        Route::post('/', [ChatController::class, 'store'])->name('store');
        Route::get('/{id}', [ChatController::class, 'show'])->name('show');
        Route::post('/{id}/mensaje', [ChatController::class, 'enviarMensaje'])->name('mensaje.enviar');
        Route::post('/{id}/tarea', [ChatController::class, 'publicarTarea'])->name('tarea.publicar');
        Route::get('/{id}/mensajes/nuevos', [ChatController::class, 'getNuevosMensajes'])->name('mensajes.nuevos');
        Route::post('/{id}/participante', [ChatController::class, 'agregarParticipante'])->name('participante.agregar');
    });
});