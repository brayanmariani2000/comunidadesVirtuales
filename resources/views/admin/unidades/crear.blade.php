@extends('layouts.app')

@section('title', 'Crear Unidad Curricular')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-journal-plus me-2"></i>Nueva Unidad Curricular
        </h1>
        <a href="{{ route('admin.unidades') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.unidades.guardar') }}">
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="codigo_unidad" class="form-label">Código de Unidad *</label>
                            <input type="text" class="form-control @error('codigo_unidad') is-invalid @enderror" 
                                   id="codigo_unidad" name="codigo_unidad" value="{{ old('codigo_unidad') }}" 
                                   placeholder="Ej: INF-101" required>
                            @error('codigo_unidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombre_unidad" class="form-label">Nombre de la Unidad *</label>
                            <input type="text" class="form-control @error('nombre_unidad') is-invalid @enderror" 
                                   id="nombre_unidad" name="nombre_unidad" value="{{ old('nombre_unidad') }}" 
                                   placeholder="Ej: Programación I" required>
                            @error('nombre_unidad')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                      id="descripcion" name="descripcion" rows="3">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="creditos" class="form-label">Créditos *</label>
                            <input type="number" class="form-control @error('creditos') is-invalid @enderror" 
                                   id="creditos" name="creditos" value="{{ old('creditos', 0) }}" min="0" required>
                            @error('creditos')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="horas_semanales" class="form-label">Horas Semanales *</label>
                            <input type="number" class="form-control @error('horas_semanales') is-invalid @enderror" 
                                   id="horas_semanales" name="horas_semanales" value="{{ old('horas_semanales', 0) }}" min="0" required>
                            @error('horas_semanales')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="periodo_academico" class="form-label">Período Académico *</label>
                            <input type="text" class="form-control @error('periodo_academico') is-invalid @enderror" 
                                   id="periodo_academico" name="periodo_academico" value="{{ old('periodo_academico') }}" 
                                   placeholder="Ej: 2025-1" required>
                            @error('periodo_academico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save me-1"></i>Guardar Unidad
                        </button>
                        <a href="{{ route('admin.unidades') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle me-1"></i>Cancelar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
