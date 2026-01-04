@extends('layouts.app')

@section('title', 'Gestión de Unidades Curriculares')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-journal-text me-2"></i>Unidades Curriculares
        </h1>
        <a href="{{ route('admin.unidades.crear') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nueva Unidad
        </a>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.unidades') }}">
                <div class="row">
                    <div class="col-md-6">
                        <input type="text" name="busqueda" class="form-control" 
                               placeholder="Buscar por código o nombre..." value="{{ request('busqueda') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Buscar
                        </button>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('admin.unidades') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle me-1"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Unidades Curriculares</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Créditos</th>
                            <th>Horas/Sem</th>
                            <th>Período</th>
                            <th>Profesores</th>
                            <th>Estudiantes</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unidades as $unidad)
                        <tr>
                            <td><strong>{{ $unidad->codigo_unidad }}</strong></td>
                            <td>{{ $unidad->nombre_unidad }}</td>
                            <td>{{ $unidad->creditos }}</td>
                            <td>{{ $unidad->horas_semanales }}</td>
                            <td>{{ $unidad->periodo_academico }}</td>
                            <td><span class="badge bg-info">{{ $unidad->total_profesores }}</span></td>
                            <td><span class="badge bg-success">{{ $unidad->total_estudiantes }}</span></td>
                            <td>
                                @if($unidad->activo)
                                <span class="badge bg-success">Activa</span>
                                @else
                                <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.unidades.editar', $unidad->id_unidad) }}" 
                                   class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.unidades.eliminar', $unidad->id_unidad) }}" 
                                      method="POST" class="d-inline" 
                                      onsubmit="return confirm('¿Desactivar esta unidad?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">No se encontraron unidades curriculares</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $unidades->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
