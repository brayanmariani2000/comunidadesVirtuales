@extends('layouts.app')

@section('title', 'Crear Unidad Curricular por Lote')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-plus-square me-2"></i>Crear Unidad Curricular
        </h1>
        <a href="{{ route('profesor.unidades') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Información de la Unidad y Lista de Estudiantes</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profesor.unidades.store.lote') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">Datos de la Unidad</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="codigo_unidad" class="form-label">Código de la Unidad</label>
                                    <input type="text" class="form-control" id="codigo_unidad" name="codigo_unidad" required placeholder="Ej: INF-1234">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nombre_unidad" class="form-label">Nombre de la Unidad</label>
                                    <input type="text" class="form-control" id="nombre_unidad" name="nombre_unidad" required placeholder="Ej: Programación I">
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="descripcion" class="form-label">Descripción (Opcional)</label>
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <h5 class="border-bottom pb-2">Importar Estudiantes (PDF)</h5>
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Formato requerido:</strong> El archivo PDF debe contener listas de estudiantes con el siguiente formato por línea (o claramente distinguible):<br>
                                <code>Cédula Nombre Apellido Correo</code><br>
                                <small>El sistema intentará extraer automáticamente estos datos. Si el usuario ya existe, se inscribirá; si no, se creará una cuenta nueva.</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="archivo_pdf" class="form-label">Seleccionar Archivo PDF</label>
                                <input class="form-control" type="file" id="archivo_pdf" name="archivo_pdf" accept="application/pdf" required>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Crear Unidad e Inscribir Estudiantes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
