@extends('layouts.app')

@section('title', 'Dashboard Profesor')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-person-badge me-2"></i>Dashboard Profesor
        </h1>
        <div class="d-flex align-items-center">
            <span class="badge bg-info fs-6 me-3">
                <i class="bi bi-person-video2 me-1"></i>Profesor
            </span>
            <span class="text-muted">
                {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
            </span>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Unidades Asignadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['unidades'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-journals fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Estudiantes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['estudiantes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Chats Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['chats'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-chat-left-text fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Mensajes Recientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($ultimosMensajes) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-envelope fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-lightning-charge-fill me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('chat.create') }}" class="btn btn-primary w-100 py-3">
                                <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                                Crear Chat
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('profesor.unidades') }}" class="btn btn-info w-100 py-3 text-white">
                                <i class="bi bi-book fs-4 d-block mb-2"></i>
                                Ver Unidades
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('chat.index') }}" class="btn btn-success w-100 py-3">
                                <i class="bi bi-chat-dots fs-4 d-block mb-2"></i>
                                Mis Chats
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <button class="btn btn-warning w-100 py-3 text-white" disabled>
                                <i class="bi bi-file-earmark-text fs-4 d-block mb-2"></i>
                                Subir Material
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mis Unidades Curriculares -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-journal-text me-2"></i>Mis Unidades Curriculares
                    </h6>
                    <a href="{{ route('profesor.unidades') }}" class="btn btn-sm btn-primary">
                        Ver Todas
                    </a>
                </div>
                <div class="card-body">
                    @forelse($unidades as $unidad)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">
                                    <strong>{{ $unidad->codigo_unidad }}</strong> - {{ $unidad->nombre_unidad }}
                                </h5>
                                <p class="mb-2 text-muted">{{ $unidad->descripcion }}</p>
                                <div class="d-flex gap-3">
                                    <span class="badge bg-primary">{{ $unidad->creditos }} créditos</span>
                                    <span class="badge bg-info">{{ $unidad->horas_semanales }}h/semana</span>
                                    <span class="badge bg-secondary">{{ ucfirst($unidad->rol_profesor) }}</span>
                                </div>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('profesor.unidades') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <p class="text-muted">No tienes unidades asignadas actualmente</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Mensajes Recientes -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-chat-square-text me-2"></i>Mensajes Recientes
                    </h6>
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @forelse($ultimosMensajes as $mensaje)
                    <div class="border-bottom pb-2 mb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <strong class="d-block">{{ $mensaje->nombre }} {{ $mensaje->apellido }}</strong>
                                <small class="text-muted">{{ $mensaje->nombre_chat }}</small>
                                <p class="mb-1 mt-1 text-truncate">{{ Str::limit($mensaje->contenido_texto, 50) }}</p>
                                <small class="text-muted">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ \Carbon\Carbon::parse($mensaje->fecha_envio)->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="bi bi-chat-left-dots fs-1 text-muted d-block mb-2"></i>
                        <p class="text-muted small mb-0">No hay mensajes recientes</p>
                    </div>
                    @endforelse
                </div>
                @if(count($ultimosMensajes) > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('chat.index') }}" class="small">Ver todos los chats</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection