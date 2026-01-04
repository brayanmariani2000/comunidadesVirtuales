@extends('layouts.app')

@section('title', 'Crear Usuario')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-person-plus me-2"></i>Crear Usuario
        </h1>
        <a href="{{ route('admin.usuarios') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.usuarios.guardar') }}">
                @csrf

                <div class="row">
                    <!-- Información Básica -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Información Personal</h5>
                        
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre *</label>
                            <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                   id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="apellido" class="form-label">Apellido *</label>
                            <input type="text" class="form-control @error('apellido') is-invalid @enderror" 
                                   id="apellido" name="apellido" value="{{ old('apellido') }}" required>
                            @error('apellido')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="cedula" class="form-label">Cédula *</label>
                            <input type="text" class="form-control @error('cedula') is-invalid @enderror" 
                                   id="cedula" name="cedula" value="{{ old('cedula') }}" required>
                            @error('cedula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control @error('telefono') is-invalid @enderror" 
                                   id="telefono" name="telefono" value="{{ old('telefono') }}">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Información de Cuenta -->
                    <div class="col-md-6">
                        <h5 class="mb-3">Información de Cuenta</h5>

                        <div class="mb-3">
                            <label for="correo" class="form-label">Correo Electrónico *</label>
                            <input type="email" class="form-control @error('correo') is-invalid @enderror" 
                                   id="correo" name="correo" value="{{ old('correo') }}" required>
                            @error('correo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="contrasena" class="form-label">Contraseña *</label>
                            <input type="password" class="form-control @error('contrasena') is-invalid @enderror" 
                                   id="contrasena" name="contrasena" required>
                            @error('contrasena')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Mínimo 6 caracteres</small>
                        </div>

                        <div class="mb-3">
                            <label for="id_rol" class="form-label">Rol *</label>
                            <select class="form-select @error('id_rol') is-invalid @enderror" 
                                    id="id_rol" name="id_rol" required onchange="mostrarCamposAdicionales()">
                                <option value="">Seleccione un rol</option>
                                @foreach($roles as $rol)
                                <option value="{{ $rol->id_rol }}" {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}>
                                    {{ $rol->nombre_rol }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_rol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Campos adicionales para Profesor -->
                <div id="campos-profesor" class="row mt-3" style="display: none;">
                    <div class="col-12">
                        <h5 class="mb-3">Información de Profesor</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="especialidad" class="form-label">Especialidad</label>
                            <input type="text" class="form-control" id="especialidad" name="especialidad" value="{{ old('especialidad') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="grado_academico" class="form-label">Grado Académico</label>
                            <input type="text" class="form-control" id="grado_academico" name="grado_academico" value="{{ old('grado_academico') }}">
                        </div>
                    </div>
                </div>

                <!-- Campos adicionales para Estudiante -->
                <div id="campos-estudiante" class="row mt-3" style="display: none;">
                    <div class="col-12">
                        <h5 class="mb-3">Información de Estudiante</h5>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="carrera" class="form-label">Carrera</label>
                            <input type="text" class="form-control" id="carrera" name="carrera" value="{{ old('carrera') }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="semestre_actual" class="form-label">Semestre Actual</label>
                            <input type="number" class="form-control" id="semestre_actual" name="semestre_actual" value="{{ old('semestre_actual', 1) }}" min="1">
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Guardar Usuario
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

<script>
function mostrarCamposAdicionales() {
    const rol = document.getElementById('id_rol').value;
    const camposProfesor = document.getElementById('campos-profesor');
    const camposEstudiante = document.getElementById('campos-estudiante');
    
    camposProfesor.style.display = 'none';
    camposEstudiante.style.display = 'none';
    
    if (rol == 2) { // Profesor
        camposProfesor.style.display = 'block';
    } else if (rol == 3) { // Estudiante
        camposEstudiante.style.display = 'block';
    }
}

// Ejecutar al cargar si hay valor previo
document.addEventListener('DOMContentLoaded', mostrarCamposAdicionales);
</script>
@endsection
