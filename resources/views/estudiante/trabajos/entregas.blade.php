@extends('layouts.app')

@section('title', 'Mis Entregas')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-file-earmark-check me-2"></i>Mis Entregas
        </h1>
        <a href="{{ route('estudiante.trabajos.entregar') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i>Entregar Trabajo
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Historial de Entregas</h6>
            <span class="badge bg-primary">{{ $entregas->count() }} entregas</span>
        </div>
        <div class="card-body">
            @if($entregas->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Unidad</th>
                            <th>Fecha de Entrega</th>
                            <th>Archivo</th>
                            <th>Estado</th>
                            <th>Calificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entregas as $entrega)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $entrega->titulo }}</div>
                                @if($entrega->descripcion)
                                <small class="text-muted">{{ Str::limit($entrega->descripcion, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $entrega->nombre_unidad }}</span>
                            </td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y') }}</small>
                                <br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $entrega->ruta_archivo) }}" 
                                   class="btn btn-sm btn-outline-primary" 
                                   target="_blank"
                                   download>
                                    <i class="bi bi-download me-1"></i>{{ $entrega->nombre_archivo }}
                                </a>
                            </td>
                            <td>
                                @if($entrega->estado == 'pendiente')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock me-1"></i>Pendiente
                                    </span>
                                @elseif($entrega->estado == 'revisado')
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Revisado
                                    </span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($entrega->estado) }}</span>
                                @endif
                            </td>
                            <td>
                                @if($entrega->calificacion !== null)
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-success fs-6 me-2">{{ $entrega->calificacion }}</span>
                                        @if($entrega->comentarios_profesor)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#comentarioModal{{ $entrega->id_entrega }}">
                                            <i class="bi bi-chat-left-text"></i>
                                        </button>
                                        @endif
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal para comentario del profesor -->
                        @if($entrega->comentarios_profesor)
                        <div class="modal fade" id="comentarioModal{{ $entrega->id_entrega }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">
                                            <i class="bi bi-chat-left-text me-2"></i>Comentario del Profesor
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-2"><strong>Trabajo:</strong> {{ $entrega->titulo }}</p>
                                        <p class="mb-2"><strong>Calificación:</strong> <span class="badge bg-success">{{ $entrega->calificacion }}</span></p>
                                        <hr>
                                        <p class="mb-0">{{ $entrega->comentarios_profesor }}</p>
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
            @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted mb-3 d-block"></i>
                <h5 class="text-muted mb-3">No has entregado trabajos aún</h5>
                <p class="text-muted mb-4">Comienza entregando tu primer trabajo</p>
                <a href="{{ route('estudiante.trabajos.entregar') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Entregar Trabajo
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas -->
    @if($entregas->count() > 0)
    <div class="row">
        <div class="col-md-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pendientes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $entregas->where('estado', 'pendiente')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock fs-2 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Calificados</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $entregas->whereNotNull('calificacion')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle fs-2 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Promedio</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                @php
                                    $calificados = $entregas->whereNotNull('calificacion');
                                    $promedio = $calificados->count() > 0 ? round($calificados->avg('calificacion'), 1) : 0;
                                @endphp
                                {{ $promedio }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-graph-up fs-2 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
