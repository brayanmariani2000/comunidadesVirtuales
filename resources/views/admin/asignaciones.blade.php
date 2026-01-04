@extends('layouts.app')

@section('title', 'Gestión de Asignaciones')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-link-45deg me-2"></i>Asignaciones Profesor-Unidad
        </h1>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaAsignacion">
            <i class="bi bi-plus-circle me-1"></i>Nueva Asignación
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

    <!-- Tabla de Asignaciones -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Asignaciones Activas</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Profesor</th>
                            <th>Unidad Curricular</th>
                            <th>Código</th>
                            <th>Rol</th>
                            <th>Fecha Asignación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($asignaciones as $asignacion)
                        <tr>
                            <td>{{ $asignacion->nombre }} {{ $asignacion->apellido }}</td>
                            <td>{{ $asignacion->nombre_unidad }}</td>
                            <td><strong>{{ $asignacion->codigo_unidad }}</strong></td>
                            <td>
                                <span class="badge 
                                    @if($asignacion->rol_profesor == 'titular') bg-primary
                                    @elseif($asignacion->rol_profesor == 'asistente') bg-info
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($asignacion->rol_profesor) }}
                                </span>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($asignacion->fecha_asignacion)->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay asignaciones registradas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Nueva Asignación -->
<div class="modal fade" id="modalNuevaAsignacion" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.asignaciones.guardar') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nueva Asignación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_profesor" class="form-label">Profesor *</label>
                        <select class="form-select" id="id_profesor" name="id_profesor" required>
                            <option value="">Seleccione un profesor</option>
                            @foreach($profesores as $profesor)
                            <option value="{{ $profesor->id_profesor }}">
                                {{ $profesor->nombre }} {{ $profesor->apellido }}
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

                    <div class="mb-3">
                        <label for="rol_profesor" class="form-label">Rol del Profesor *</label>
                        <select class="form-select" id="rol_profesor" name="rol_profesor" required>
                            <option value="titular">Titular</option>
                            <option value="asistente">Asistente</option>
                            <option value="auxiliar">Auxiliar</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Asignación</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
