@extends('layouts.app')

@section('title', 'Calificaciones')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-award me-2"></i>Mis Calificaciones
        </h1>
        <a href="{{ route('estudiante.trabajos.entregas') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Todas las Entregas
        </a>
    </div>

    @if($calificaciones->count() > 0)
    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Promedio General</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ round($calificaciones->avg('calificacion'), 1) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fs-2 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Calificación Más Alta</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $calificaciones->max('calificacion') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-trophy fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Calificación Más Baja</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $calificaciones->min('calificacion') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-arrow-down-circle fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Calificados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $calificaciones->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fs-2 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Calificaciones -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Trabajos Calificados</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Trabajo</th>
                            <th>Unidad</th>
                            <th>Fecha de Entrega</th>
                            <th>Calificación</th>
                            <th>Comentario</th>
                            <th>Archivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calificaciones as $calificacion)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $calificacion->titulo }}</div>
                                @if($calificacion->descripcion)
                                <small class="text-muted">{{ Str::limit($calificacion->descripcion, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $calificacion->nombre_unidad }}</span>
                            </td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($calificacion->fecha_entrega)->format('d/m/Y H:i') }}</small>
                            </td>
                            <td>
                                @php
                                    $nota = $calificacion->calificacion;
                                    $badgeClass = $nota >= 80 ? 'bg-success' : ($nota >= 60 ? 'bg-warning text-dark' : 'bg-danger');
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6">{{ $nota }}</span>
                            </td>
                            <td>
                                @if($calificacion->comentarios_profesor)
                                <button type="button" 
                                        class="btn btn-sm btn-outline-primary" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#comentarioModal{{ $calificacion->id_entrega }}">
                                    <i class="bi bi-chat-left-text me-1"></i>Ver Comentario
                                </button>
                                @else
                                <span class="text-muted">Sin comentarios</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $calificacion->ruta_archivo) }}" 
                                   class="btn btn-sm btn-outline-secondary" 
                                   target="_blank"
                                   download>
                                    <i class="bi bi-download"></i>
                                </a>
                            </td>
                        </tr>

                        <!-- Modal para comentario -->
                        @if($calificacion->comentarios_profesor)
                        <div class="modal fade" id="comentarioModal{{ $calificacion->id_entrega }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header bg-primary text-white">
                                        <h5 class="modal-title">
                                            <i class="bi bi-chat-left-text me-2"></i>Retroalimentación del Profesor
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <strong>Trabajo:</strong> {{ $calificacion->titulo }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Unidad:</strong> {{ $calificacion->nombre_unidad }}
                                        </div>
                                        <div class="mb-3">
                                            <strong>Calificación:</strong> 
                                            <span class="badge {{ $badgeClass }} fs-6">{{ $calificacion->calificacion }}</span>
                                        </div>
                                        <hr>
                                        <div>
                                            <strong>Comentario:</strong>
                                            <p class="mt-2 mb-0">{{ $calificacion->comentarios_profesor }}</p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <!-- Estado vacío -->
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <i class="bi bi-award fs-1 text-muted mb-3 d-block"></i>
            <h5 class="text-muted mb-3">No tienes calificaciones aún</h5>
            <p class="text-muted mb-4">Tus trabajos calificados aparecerán aquí</p>
            <a href="{{ route('estudiante.trabajos.entregar') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i>Entregar un Trabajo
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
