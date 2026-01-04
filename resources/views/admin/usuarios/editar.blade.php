@extends('layouts.app')

@section('title', 'Editar Usuario')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-pencil-square me-2"></i>Editar Usuario
        </h1>
        <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.usuarios.actualizar', $usuario->id_usuario) }}">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-3">Información Personal</h5>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre', $usuario->nombre) }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido *</label>
                            <input type="text" class="form-control @error('apellido') is-invalid @enderror" 
                                   id="apellido" name="apellido" value="{{ old('apellido', $usuario->apellido) }}" required>
                            @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cédula *</label>
                            <input type="text" class="form-control @error('cedula') is-invalid @enderror" 
                                   id="cedula" name="cedula" value="{{ old('cedula', $usuario->cedula) }}" required>
                            @error('cedula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" value="{{ old('telefono', $usuario->telefono) }}">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico *</label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                                   id="correo" name="correo" value="{{ old('correo', $usuario->correo) }}" required>
                            @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Información de Cuenta</h5>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control @error('contrasena') is-invalid @enderror" 
                                   id="contrasena" name="contrasena">
                            @error('contrasena')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Dejar en blanco para mantener la contraseña actual</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Rol Actual</label>
                            <input type="text" class="form-control" value="{{ $roles->firstWhere('id_rol', $usuario->id_rol)->nombre_rol }}" disabled>
                            <small class="text-muted">El rol no se puede cambiar después de crear el usuario</small>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="activo" id="activo" 
                                       {{ $usuario->activo ? 'checked' : '' }}>
                                <label class="form-check-label" for="activo">
                                    Usuario Activo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                @if($datosAdicionales && $usuario->id_rol == 2)
                <!-- Campos Profesor -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h5 class="mb-3">Información de Profesor</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="especialidad" class="form-label">Especialidad</label>
                            <input type="text" class="form-control" id="especialidad" name="especialidad" 
                                   value="{{ old('especialidad', $datosAdicionales->especialidad) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="grado_academico" class="form-label">Grado Académico</label>
                            <input type="text" class="form-control" id="grado_academico" name="grado_academico" 
                                   value="{{ old('grado_academico', $datosAdicionales->grado_academico) }}">
                        </div>
                    </div>
                </div>
                @endif

                @if($datosAdicionales && $usuario->id_rol == 3)
                <!-- Campos Estudiante -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h5 class="mb-3">Información de Estudiante</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="carrera" class="form-label">Carrera</label>
                            <input type="text" class="form-control" id="carrera" name="carrera" 
                                   value="{{ old('carrera', $datosAdicionales->carrera) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="semestre_actual" class="form-label">Semestre Actual</label>
                            <input type="number" class="form-control" id="semestre_actual" name="semestre_actual" 
                                   value="{{ old('semestre_actual', $datosAdicionales->semestre_actual) }}" min="1">
                        </div>
                    </div>
                </div>
                @endif

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Actualizar Usuario
                        </button>
                        <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
