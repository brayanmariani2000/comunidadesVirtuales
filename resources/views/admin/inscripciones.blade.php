@extends('layouts.app')

@section('title', 'Gestión de Inscripciones')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-person-check me-2"></i>Inscripciones Estudiante-Unidad
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaInscripcion">
            <i class="bi bi-plus-circle me-1"></i>Nueva Inscripción
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Tabla de Inscripciones -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Inscripciones Registradas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-sm">
                    <thead>
                        <tr>
                            <th>Código Est.</th>
                            <th>Estudiante</th>
                            <th>Código Unidad</th>
                            <th>Unidad Curricular</th>
                            <th>Estado</th>
                            <th>Calificación</th>
                            <th>Fecha Inscripción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inscripciones as $inscripcion)
                        <tr>
                            <td><strong>{{ $inscripcion->codigo_estudiante }}</strong></td>
                            <td>{{ $inscripcion->nombre }} {{ $inscripcion->apellido }}</td>
                            <td>{{ $inscripcion->codigo_unidad }}</td>
                            <td>{{ $inscripcion->nombre_unidad }}</td>
                            <td>
                                <span class="badge 
                                    @if($inscripcion->estado == 'activo') bg-success
                                    @elseif($inscripcion->estado == 'finalizado') bg-primary
                                    @elseif($inscripcion->estado == 'retirado') bg-warning
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($inscripcion->estado) }}
                                </span>
                            </td>
                            <td>
                                @if($inscripcion->calificacion_final)
                                    <strong>{{ number_format($inscripcion->calificacion_final, 2) }}</strong>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($inscripcion->fecha_inscripcion)->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No hay inscripciones registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $inscripciones->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Inscripción -->
<div class="modal fade" id="modalNuevaInscripcion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.inscripciones.guardar') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Inscripción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_estudiante" class="form-label">Estudiante *</label>
                        <select class="form-select" id="id_estudiante" name="id_estudiante" required>
                            <option value="">Seleccione un estudiante</option>
                            @foreach($estudiantes as $estudiante)
                            <option value="{{ $estudiante->id_estudiante }}">
                                {{ $estudiante->codigo_estudiante }} - {{ $estudiante->nombre }} {{ $estudiante->apellido }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="id_unidad" class="form-label">Unidad Curricular *</label>
                        <select class="form-select" id="id_unidad" name="id_unidad" required>
                            <option value="">Seleccione una unidad</option>
                            @foreach($unidades as $unidad)
                            <option value="{{ $unidad->id_unidad }}">
                                {{ $unidad->codigo_unidad }} - {{ $unidad->nombre_unidad }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Inscripción</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
