@extends('layouts.app')

@section('title', 'Dashboard Estudiante')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-person-circle me-2"></i>Dashboard Estudiante
        </h1>
        <div class="d-flex align-items-center">
            <span class="badge bg-success fs-6 me-3">
                <i class="bi bi-mortarboard me-1"></i>Estudiante
            </span>
            <span class="text-muted">
                {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
            </span>
        </div>
    </div>

    <!-- Bienvenida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="h5 font-weight-bold text-primary mb-1">¡Bienvenido, {{ Auth::user()->nombre }}!</div>
                            <p class="mb-0 text-gray-800">
                                Matrícula: {{ Auth::user()->cedula }} | 
                                Correo: {{ Auth::user()->correo }}
                            </p>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-mortarboard-fill fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Unidades Inscritas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($unidades) }}</div>
                            <div class="mt-2 text-xs text-muted">
                                @if(count($unidades) > 0)
                                    {{ count($unidades) }} unidad{{ count($unidades) > 1 ? 'es' : '' }} activa{{ count($unidades) > 1 ? 's' : '' }}
                                @else
                                    No tienes unidades inscritas
                                @endif
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-journal-text fs-1 text-gray-300"></i>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $chatsActivos }}</div>
                            <div class="mt-2 text-xs text-muted">
                                {{ $chatsActivos }} chat{{ $chatsActivos != 1 ? 's' : '' }} disponible{{ $chatsActivos != 1 ? 's' : '' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-chat-dots fs-1 text-gray-300"></i>
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
                                Mensajes Sin Leer
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $mensajesSinLeer }}</div>
                            <div class="mt-2 text-xs text-muted">
                                {{ $mensajesSinLeer }} mensaje{{ $mensajesSinLeer != 1 ? 's' : '' }} pendiente{{ $mensajesSinLeer != 1 ? 's' : '' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-envelope fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Profesores
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($profesores) }}</div>
                            <div class="mt-2 text-xs text-muted">
                                {{ count($profesores) }} profesor{{ count($profesores) != 1 ? 'es' : '' }} asignado{{ count($profesores) != 1 ? 's' : '' }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-video2 fs-1 text-gray-300"></i>
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
                    <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('chat.index') }}" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-chat-left-text fs-2 me-2"></i>
                                <div>
                                    <div class="fw-bold">Ir al</div>
                                    <small>Chat</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('estudiante.unidades') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-journal-text fs-2 me-2"></i>
                                <div>
                                    <div class="fw-bold">Mis</div>
                                    <small>Unidades</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('estudiante.trabajos') }}" class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-file-earmark-text fs-2 me-2"></i>
                                <div>
                                    <div class="fw-bold">Mis</div>
                                    <small>Trabajos</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-person-circle fs-2 me-2"></i>
                                <div>
                                    <div class="fw-bold">Mi</div>
                                    <small>Perfil</small>
                                </div>
                            </a>
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
                    <h6 class="m-0 font-weight-bold text-primary">Mis Unidades Curriculares</h6>
                    @if(count($unidades) > 0)
                        <a href="{{ route('estudiante.unidades') }}" class="btn btn-sm btn-primary">
                            Ver Todas
                        </a>
                    @endif
                </div>
                <div class="card-body">
                    @if(count($unidades) > 0)
                        <div class="row">
                            @foreach($unidades->take(3) as $unidad)
                            <div class="col-md-6 mb-3">
                                <div class="card h-100 border-left-primary">
                                    <div class="card-body">
                                        <h6 class="card-title text-primary">
                                            <strong>{{ $unidad->codigo_unidad ?? 'N/A' }}</strong>
                                        </h6>
                                        <h6 class="card-subtitle mb-2 text-dark">
                                            {{ $unidad->nombre_unidad ?? 'Unidad sin nombre' }}
                                        </h6>
                                        <p class="card-text small text-muted mb-2">
                                            {{ Str::limit($unidad->descripcion ?? 'Sin descripción', 80) }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                @if($unidad->profesor)
                                                    <i class="bi bi-person me-1"></i>
                                                    {{ $unidad->profesor->nombre ?? '' }} 
                                                    {{ $unidad->profesor->apellido ?? '' }}
                                                @else
                                                    <i class="bi bi-person-x me-1"></i>
                                                    Sin profesor asignado
                                                @endif
                                            </small>
                                            <a href="{{ $unidad->id_chat ? route('chat.show', $unidad->id_chat) : route('chat.index') }}" class="btn btn-sm btn-outline-primary" title="Ir al Chat">
                                                <i class="bi bi-chat"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        @if(count($unidades) > 3)
                            <div class="text-center mt-3">
                                <a href="{{ route('estudiante.unidades') }}" class="btn btn-link">
                                    Ver las {{ count($unidades) - 3 }} unidades restantes
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-journal-x fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="text-muted">No tienes unidades inscritas</h5>
                            <p class="text-muted small">Contacta con tu coordinador académico</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Mensajes Recientes -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-chat-square-text me-2"></i>Mensajes Recientes
                    </h6>
                    @if(count($mensajesRecientes) > 0)
                        <span class="badge bg-primary">{{ count($mensajesRecientes) }}</span>
                    @endif
                </div>
                <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                    @if(count($mensajesRecientes) > 0)
                        @foreach($mensajesRecientes as $mensaje)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center mb-1">
                                        @if($mensaje->usuario)
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 25px; height: 25px; font-size: 0.8rem;">
                                                {{ strtoupper(substr($mensaje->usuario->nombre, 0, 1)) }}
                                            </div>
                                            <strong class="small">{{ $mensaje->usuario->nombre ?? 'Usuario' }}</strong>
                                        @endif
                                    </div>
                                    @if($mensaje->chat)
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-chat-left me-1"></i>
                                            {{ $mensaje->chat->nombre_chat ?? 'Chat general' }}
                                        </small>
                                    @endif
                                    <p class="mb-1 small text-truncate">
                                        {{ Str::limit($mensaje->contenido_texto ?? 'Sin contenido', 40) }}
                                    </p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        @if($mensaje->fecha_envio)
                                            {{ \Carbon\Carbon::parse($mensaje->fecha_envio)->diffForHumans() }}
                                        @else
                                            Reciente
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-chat-left-dots fs-1 text-muted d-block mb-2"></i>
                            <p class="text-muted small mb-0">No hay mensajes recientes</p>
                            <a href="{{ route('chat.index') }}" class="btn btn-sm btn-outline-primary mt-2">
                                <i class="bi bi-plus-circle me-1"></i>
                                Iniciar conversación
                            </a>
                        </div>
                    @endif
                </div>
                @if(count($mensajesRecientes) > 0)
                <div class="card-footer text-center">
                    <a href="{{ route('chat.index') }}" class="small">
                        <i class="bi bi-arrow-right me-1"></i>
                        Ver todos los chats
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Profesores -->
    @if(count($profesores) > 0)
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="bi bi-person-video2 me-2"></i>Mis Profesores
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($profesores as $profesor)
                        <div class="col-md-4 col-lg-3 mb-3">
                            <div class="card border-left-info h-100">
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <div class="rounded-circle bg-info text-white d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                    </div>
                                    <h6 class="card-title text-center mb-1">
                                        {{ $profesor->nombre }} {{ $profesor->apellido }}
                                    </h6>
                                    <p class="card-text text-center small text-muted mb-2">
                                        {{ $profesor->correo }}
                                    </p>
                                    <div class="text-center">
                                        <a href="{{ route('chat.index') }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-chat me-1"></i>
                                            Contactar
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection