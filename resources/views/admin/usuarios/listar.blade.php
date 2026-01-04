@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-people me-2"></i>Gestión de Usuarios
        </h1>
        <a href="{{ route('admin.usuarios.crear') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-1"></i>Nuevo Usuario
        </a>
    </div>

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.usuarios') }}">
                <div class="row">
                    <div class="col-md-4">
                        <input type="text" name="busqueda" class="form-control" placeholder="Buscar por nombre, correo o cédula..." value="{{ request('busqueda') }}">
                    </div>
                    <div class="col-md-3">
                        <select name="rol" class="form-select">
                            <option value="">Todos los roles</option>
                            @foreach($roles as $rol)
                            <option value="{{ $rol->id_rol }}" {{ request('rol') == $rol->id_rol ? 'selected' : '' }}>
                                {{ $rol->nombre_rol }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i>Buscar
                        </button>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary w-100">
                            <i class="bi bi-x-circle me-1"></i>Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Usuarios</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Cédula</th>
                            <th>Correo</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $usuario->id_usuario }}</td>
                            <td>{{ $usuario->nombre }} {{ $usuario->apellido }}</td>
                            <td>{{ $usuario->cedula }}</td>
                            <td>{{ $usuario->correo }}</td>
                            <td>
                                <span class="badge 
                                    @if($usuario->id_rol == 1) bg-danger
                                    @elseif($usuario->id_rol == 2) bg-info
                                    @else bg-success
                                    @endif">
                                    {{ $usuario->nombre_rol }}
                                </span>
                            </td>
                            <td>
                                @if($usuario->activo)
                                <span class="badge bg-success">Activo</span>
                                @else
                                <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($usuario->fecha_registro)->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.usuarios.editar', $usuario->id_usuario) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.usuarios.eliminar', $usuario->id_usuario) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de desactivar este usuario?');">
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
                            <td colspan="8" class="text-center">No se encontraron usuarios</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-3">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
