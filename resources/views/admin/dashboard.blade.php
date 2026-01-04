@extends('layouts.app')

@section('title', 'Panel de Administración')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-speedometer2 me-2"></i>Panel de Administración
        </h1>
        <div class="d-flex align-items-center">
            <span class="badge bg-danger fs-6 me-3">
                <i class="bi bi-shield-check me-1"></i>Administrador
            </span>
            <span class="text-muted">
                {{ Auth::user()->nombre }} {{ Auth::user()->apellido }}
            </span>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Usuarios
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['total_usuarios'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fs-1 text-gray-300"></i>
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
                                Profesores
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['total_profesores'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-person-video2 fs-1 text-gray-300"></i>
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
                                Estudiantes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['total_estudiantes'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-mortarboard fs-1 text-gray-300"></i>
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
                                Unidades Curriculares
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $estadisticas['total_unidades'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-journal-text fs-1 text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Accesos Rápidos -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Accesos Rápidos</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.usuarios') }}" class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-people fs-2 me-2"></i>
                                <div>
                                    <div class="fw-bold">Gestionar</div>
                                    <small>Usuarios</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.unidades') }}" class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-journal-text fs-2 me-2"></i>
                                <div>
                                    <div class="fw-bold">Gestionar</div>
                                    <small>Unidades</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.asignaciones') }}" class="btn btn-outline-info w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-link-45deg fs-2 me-2"></i>
                                <div>
                                    <div class="fw-bold">Gestionar</div>
                                    <small>Asignaciones</small>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('admin.reportes') }}" class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center py-3">
                                <i class="bi bi-bar-chart fs-2 me-2"></i>
                                <div>
                                    <div class="fw-bold">Ver</div>
                                    <small>Reportes</small>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Otros Datos -->
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actividad del Sistema</h6>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @forelse($actividad_reciente as $log)
                        <div class="list-group-item d-flex px-0">
                            <div class="me-3">
                                @if($log->nivel == 'error' || $log->nivel == 'critical')
                                    <i class="bi bi-exclamation-octagon-fill text-danger fs-4"></i>
                                @elseif($log->nivel == 'warning')
                                    <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                                @else
                                    <i class="bi bi-info-circle-fill text-primary fs-4"></i>
                                @endif
                            </div>
                            <div class="w-100">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-gray-800">{{ $log->accion }}</strong>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($log->fecha_hora)->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 small">{{ $log->descripcion }}</p>
                                <div class="d-flex justify-content-between small">
                                    <span class="text-primary">{{ $log->modulo }}</span>
                                    @if($log->nombre)
                                        <span class="text-muted">Por: {{ $log->nombre }} {{ $log->apellido }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-muted py-3">No hay actividad reciente registrada.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Usuarios Recientes</h6>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        @forelse($usuarios_recientes as $usuario)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $usuario->nombre }} {{ $usuario->apellido }}</strong>
                                <br>
                                <small class="text-muted">{{ $usuario->correo }}</small>
                            </div>
                            <span class="badge bg-primary">{{ $usuario->nombre_rol ?? 'Sin rol' }}</span>
                        </div>
                        @empty
                        <p class="text-muted">No hay usuarios recientes</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection