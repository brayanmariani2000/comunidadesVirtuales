@extends('layouts.app')

@section('title', 'Entregar Trabajo')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-upload me-2"></i>Entregar Trabajo
        </h1>
        <a href="{{ route('estudiante.trabajos.entregas') }}" class="btn btn-outline-secondary">
            <i class="bi bi-list-ul me-1"></i>Mis Entregas
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="bi bi-file-earmark-arrow-up me-2"></i>Formulario de Entrega
                    </h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('estudiante.trabajos.entregar.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        @if(isset($tarea))
                            <input type="hidden" name="id_tarea" value="{{ $tarea->id_tarea }}">
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                Entregando para la tarea: <strong>{{ $tarea->titulo }}</strong>
                            </div>
                        @endif

                        <!-- Unidad Curricular -->
                        <div class="mb-4">
                            <label for="id_unidad" class="form-label fw-bold">
                                <i class="bi bi-book me-1"></i>Unidad Curricular <span class="text-danger">*</span>
                            </label>
                            <select name="id_unidad" id="id_unidad" class="form-select @error('id_unidad') is-invalid @enderror" required>
                                <option value="">Seleccione una unidad...</option>
                                @foreach($unidades as $unidad)
                                <option value="{{ $unidad->id_unidad }}" 
                                    {{ (old('id_unidad') == $unidad->id_unidad || (isset($tarea) && $tarea->id_unidad == $unidad->id_unidad)) ? 'selected' : '' }}>
                                    {{ $unidad->codigo_unidad }} - {{ $unidad->nombre_unidad }}
                                </option>
                                @endforeach
                            </select>
                            @error('id_unidad')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Título -->
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-bold">
                                <i class="bi bi-card-heading me-1"></i>Título del Trabajo <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   name="titulo" 
                                   id="titulo" 
                                   class="form-control @error('titulo') is-invalid @enderror" 
                                   value="{{ old('titulo', isset($tarea) ? 'Entrega: ' . $tarea->titulo : '') }}"
                                   placeholder="Ej: Trabajo Práctico N°1 - Análisis de Sistemas"
                                   required
                                   maxlength="255">
                            @error('titulo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcion" class="form-label fw-bold">
                                <i class="bi bi-text-paragraph me-1"></i>Descripción
                            </label>
                            <textarea name="descripcion" 
                                      id="descripcion" 
                                      class="form-control @error('descripcion') is-invalid @enderror" 
                                      rows="4"
                                      placeholder="Agregue una descripción o comentarios sobre el trabajo (opcional)">{{ old('descripcion') }}</textarea>
                            @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Archivo -->
                        <div class="mb-4">
                            <label for="archivo" class="form-label fw-bold">
                                <i class="bi bi-paperclip me-1"></i>Archivo del Trabajo <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   name="archivo" 
                                   id="archivo" 
                                   class="form-control @error('archivo') is-invalid @enderror" 
                                   accept=".pdf,.doc,.docx,.txt,.zip,.rar"
                                   required>
                            @error('archivo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Formatos permitidos: PDF, DOC, DOCX, TXT, ZIP, RAR. Tamaño máximo: 10MB
                            </small>
                            
                            <!-- Preview del archivo -->
                            <div id="file-preview" class="mt-3" style="display: none;">
                                <div class="alert alert-info d-flex align-items-center">
                                    <i class="bi bi-file-earmark-text fs-3 me-3"></i>
                                    <div class="flex-grow-1">
                                        <strong id="file-name"></strong>
                                        <br>
                                        <small id="file-size" class="text-muted"></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ route('estudiante.trabajos') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send me-1"></i>Entregar Trabajo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Información adicional -->
            <div class="card shadow mt-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-info-circle me-2"></i>Información Importante
                    </h6>
                    <ul class="mb-0">
                        <li class="mb-2">Asegúrese de seleccionar la unidad curricular correcta.</li>
                        <li class="mb-2">El archivo debe estar en uno de los formatos permitidos.</li>
                        <li class="mb-2">Una vez entregado, el trabajo quedará en estado "Pendiente" hasta que el profesor lo califique.</li>
                        <li class="mb-2">Puede ver el estado de sus entregas en la sección "Mis Entregas".</li>
                        <li>El tamaño máximo del archivo es de 10MB.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('archivo');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');

    fileInput.addEventListener('change', function(e) {
        if (e.target.files.length > 0) {
            const file = e.target.files[0];
            fileName.textContent = file.name;
            fileSize.textContent = formatFileSize(file.size);
            filePreview.style.display = 'block';
        } else {
            filePreview.style.display = 'none';
        }
    });

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
});
</script>
@endpush
@endsection
