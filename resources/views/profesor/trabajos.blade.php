@extends('layouts.app')

@section('title', 'Trabajos de Estudiantes')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-file-earmark-text me-2"></i>Trabajos Recibidos
        </h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Entregas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered hover" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Unidad</th>
                            <th>Título</th>
                            <th>Fecha de Entrega</th>
                            <th>Archivo</th>
                            <th>Calificación</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($entregas as $entrega)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                        <i class="bi bi-person text-secondary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $entrega->nombre_estudiante }} {{ $entrega->apellido_estudiante }}</div>
                                        <small class="text-muted">{{ $entrega->correo }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $entrega->nombre_unidad }}</div>
                                @if($entrega->titulo_tarea)
                                <small class="text-primary"><i class="bi bi-tag-fill me-1"></i>{{ $entrega->titulo_tarea }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-bold">{{ $entrega->titulo }}</div>
                                @if($entrega->descripcion)
                                <small class="text-muted">{{ Str::limit($entrega->descripcion, 50) }}</small>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($entrega->fecha_entrega)->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('profesor.trabajos.descargar', $entrega->id_entrega) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-download me-1"></i>Descargar
                                </a>
                            </td>
                            <td> {{-- New column for Estado --}}
                                @if($entrega->estado == 'pendiente')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($entrega->estado == 'revisado')
                                    <span class="badge bg-success">Revisado</span>
                                @else
                                    <span class="badge bg-info">{{ ucfirst($entrega->estado) }}</span>
                                @endif
                            </td>
                            <td> {{-- Modified Calificación column --}}
                                @if($entrega->calificacion !== null)
                                    <span class="fw-bold">{{ $entrega->calificacion }}/100</span>
                                @else
                                    <span class="text-muted small">Sin calificar</span>
                                @endif
                            </td>
                            <td>
                                <button type="button"
                                        class="btn btn-sm btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#calificarModal{{ $entrega->id_entrega }}">
                                    <i class="bi bi-pencil-square"></i> {{ $entrega->calificacion ? 'Editar' : 'Calificar' }}
                                </button>
                            </td>
                        </tr>

                        <!-- Modal de Calificación -->
                        <div class="modal fade" id="calificarModal{{ $entrega->id_entrega }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form action="{{ route('profesor.trabajos.calificar.store', $entrega->id_entrega) }}" method="POST">
                                        @csrf
                                        <div class="modal-header bg-primary text-white">
                                            <h5 class="modal-title">
                                                <i class="bi bi-pencil-square me-2"></i>Calificar Trabajo
                                            </h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <strong>Estudiante:</strong> {{ $entrega->nombre_estudiante }} {{ $entrega->apellido_estudiante }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Trabajo:</strong> {{ $entrega->titulo }}
                                            </div>
                                            <div class="mb-3">
                                                <strong>Unidad:</strong> {{ $entrega->nombre_unidad }}
                                            </div>
                                            @if($entrega->descripcion)
                                            <div class="mb-3">
                                                <strong>Descripción:</strong>
                                                <p class="text-muted mb-0">{{ $entrega->descripcion }}</p>
                                            </div>
                                            @endif
                                            <hr>
                                            
                                            <div class="mb-3">
                                                <label for="calificacion{{ $entrega->id_entrega }}" class="form-label fw-bold">
                                                    Calificación (0-100) <span class="text-danger">*</span>
                                                </label>
                                                <input type="number" 
                                                       name="calificacion" 
                                                       id="calificacion{{ $entrega->id_entrega }}" 
                                                       class="form-control" 
                                                       min="0" 
                                                       max="100" 
                                                       value="{{ $entrega->calificacion ?? '' }}"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="comentarios{{ $entrega->id_entrega }}" class="form-label fw-bold">
                                                    Comentario / Retroalimentación
                                                </label>
                                                <textarea name="comentarios_profesor" 
                                                          id="comentarios{{ $entrega->id_entrega }}" 
                                                          class="form-control" 
                                                          rows="4"
                                                          placeholder="Agregue comentarios sobre el trabajo del estudiante...">{{ $entrega->comentarios_profesor ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-1"></i>Guardar Calificación
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-inbox fs-1 text-gray-300 mb-2"></i>
                                    <p class="text-muted mb-0">No se han recibido entregas de trabajos</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
