@extends('layouts.app')

@section('title', 'Entregar Trabajo')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Header -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-2 fw-semibold text-dark">Entregar Trabajo</h1>
                    <p class="text-muted mb-0">Sube tus trabajos académicos de forma segura</p>
                </div>
                <div class="badge bg-light text-dark border px-3 py-2">
                    <i class="bi bi-clock me-1"></i>
                    {{ now()->format('d/m/Y') }}
                </div>
            </div>

            <!-- Main Card -->
            <div class="card border-0 shadow-sm overflow-hidden">
                <!-- Card Header -->
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                            <i class="bi bi-file-earmark-arrow-up text-primary fs-5"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-semibold">Nueva Entrega</h5>
                            <p class="text-muted small mb-0">Completa los siguientes datos para entregar tu trabajo</p>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <form action="#" method="POST" enctype="multipart/form-data" id="upload-form">
                        @csrf

                        <!-- Unidad Curricular -->
                        <div class="mb-4">
                            <label for="unidad" class="form-label fw-medium text-dark mb-2">
                                <i class="bi bi-journal-text me-2"></i>Unidad Curricular
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-book text-muted"></i>
                                </span>
                                <select class="form-select border-start-0 ps-0" 
                                        id="unidad" 
                                        name="unidad"
                                        required>
                                    <option value="" disabled selected>Selecciona una unidad</option>
                                    @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id_unidad }}" 
                                            data-profesor="{{ $unidad->profesor_nombre ?? 'No asignado' }}"
                                            data-creditos="{{ $unidad->creditos ?? 'N/A' }}">
                                        {{ $unidad->nombre_unidad }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Unidad Details -->
                            <div class="unidad-details mt-2 p-3 bg-light rounded border" id="unidad-details" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-person me-1"></i>Profesor
                                        </small>
                                        <span class="fw-medium" id="profesor-name">-</span>
                                    </div>
                                    <div class="col-md-6">
                                        <small class="text-muted d-block mb-1">
                                            <i class="bi bi-award me-1"></i>Créditos
                                        </small>
                                        <span class="badge bg-primary bg-opacity-10 text-primary" id="creditos-count">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Archivo del trabajo -->
                        <div class="mb-4">
                            <label class="form-label fw-medium text-dark mb-2">
                                <i class="bi bi-paperclip me-2"></i>Archivo del trabajo
                            </label>
                            
                            <!-- File Drop Zone -->
                            <div class="file-drop-zone border-2 border-dashed rounded-3 p-4 text-center bg-light hover-shadow"
                                 id="file-drop-zone">
                                <div class="py-4">
                                    <div class="mb-3">
                                        <i class="bi bi-cloud-arrow-up text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                    <h6 class="mb-2">Arrastra y suelta tu archivo aquí</h6>
                                    <p class="text-muted small mb-3">o haz clic para seleccionar</p>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="browse-btn">
                                        <i class="bi bi-folder2-open me-1"></i>Explorar archivos
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Hidden file input -->
                            <input type="file" 
                                   class="d-none" 
                                   id="trabajo" 
                                   name="trabajo"
                                   accept=".pdf,.doc,.docx,.txt,.zip,.rar,.pptx,.xlsx,.jpg,.jpeg,.png"
                                   required>
                            
                            <!-- File Preview -->
                            <div class="file-preview mt-3" id="file-preview" style="display: none;">
                                <div class="card border">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="file-icon me-3">
                                                <i class="bi bi-file-earmark-text fs-3 text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <h6 class="mb-0 text-truncate" id="file-name">-</h6>
                                                    <button type="button" class="btn btn-sm btn-link text-danger p-0" 
                                                            id="remove-file">
                                                        <i class="bi bi-x-lg"></i>
                                                    </button>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted me-3" id="file-size">-</small>
                                                    <small class="text-muted" id="file-type">-</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- File Requirements -->
                            <div class="mt-3">
                                <small class="text-muted d-block mb-1">
                                    <i class="bi bi-info-circle me-1"></i>Formatos aceptados:
                                </small>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach(['PDF', 'DOC', 'DOCX', 'PPTX', 'XLSX', 'ZIP', 'JPG', 'PNG'] as $format)
                                    <span class="badge bg-light text-dark border">{{ $format }}</span>
                                    @endforeach
                                </div>
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Tamaño máximo: 50MB
                                </small>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-4">
                            <label for="comentarios" class="form-label fw-medium text-dark mb-2">
                                <i class="bi bi-chat-left-text me-2"></i>Comentarios adicionales (opcional)
                            </label>
                            <textarea class="form-control" 
                                      id="comentarios" 
                                      name="comentarios" 
                                      rows="3" 
                                      placeholder="Agrega algún comentario, observación o instrucción especial para el profesor..."></textarea>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4" id="submit-btn">
                                <i class="bi bi-check-circle me-1"></i>Entregar Trabajo
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card border-0 bg-light shadow-sm mt-4">
                <div class="card-body p-3">
                    <div class="d-flex align-items-start">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-2 me-3 flex-shrink-0">
                            <i class="bi bi-question-circle text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-2 fw-semibold">¿Necesitas ayuda?</h6>
                            <p class="text-muted small mb-2">Asegúrate de:</p>
                            <ul class="text-muted small mb-0 ps-3">
                                <li>Seleccionar la unidad curricular correcta</li>
                                <li>Subir el archivo en los formatos aceptados</li>
                                <li>Verificar que el archivo no exceda los 50MB</li>
                                <li>Revisar tu trabajo antes de enviarlo</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.file-drop-zone {
    transition: all 0.3s ease;
    cursor: pointer;
    border-color: #dee2e6;
    background-color: #f8f9fa;
}

.file-drop-zone:hover {
    border-color: #0d6efd;
    background-color: #f0f7ff;
}

.file-drop-zone.drag-over {
    border-color: #0d6efd;
    background-color: #e7f1ff;
    transform: translateY(-2px);
}

.hover-shadow:hover {
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.05);
}

.file-preview .card {
    border-left: 3px solid #0d6efd;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.form-select:focus, .form-control:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1);
    border-color: #86b7fe;
}

.btn-primary {
    padding: 0.5rem 2rem;
}

.badge.bg-primary.bg-opacity-10 {
    padding: 0.35rem 0.75rem;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const unidadSelect = document.getElementById('unidad');
    const unidadDetails = document.getElementById('unidad-details');
    const profesorName = document.getElementById('profesor-name');
    const creditosCount = document.getElementById('creditos-count');
    const fileInput = document.getElementById('trabajo');
    const fileDropZone = document.getElementById('file-drop-zone');
    const browseBtn = document.getElementById('browse-btn');
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    const fileType = document.getElementById('file-type');
    const removeFileBtn = document.getElementById('remove-file');
    const uploadForm = document.getElementById('upload-form');
    const submitBtn = document.getElementById('submit-btn');

    // Unidad select change
    if (unidadSelect) {
        unidadSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value) {
                const profesor = selectedOption.getAttribute('data-profesor');
                const creditos = selectedOption.getAttribute('data-creditos');
                
                profesorName.textContent = profesor;
                creditosCount.textContent = creditos;
                unidadDetails.style.display = 'block';
            } else {
                unidadDetails.style.display = 'none';
            }
        });
    }

    // File upload handling
    if (browseBtn && fileInput) {
        browseBtn.addEventListener('click', function() {
            fileInput.click();
        });
    }

    if (fileDropZone && fileInput) {
        // Click to browse
        fileDropZone.addEventListener('click', function() {
            fileInput.click();
        });

        // Drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            fileDropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            fileDropZone.classList.add('drag-over');
        }

        function unhighlight() {
            fileDropZone.classList.remove('drag-over');
        }

        // Handle dropped files
        fileDropZone.addEventListener('drop', function(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            
            if (files.length > 0) {
                handleFile(files[0]);
            }
        }, false);
    }

    // File input change
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0]);
            }
        });
    }

    // Handle file selection
    function handleFile(file) {
        // Validate file size (50MB max)
        const maxSize = 50 * 1024 * 1024; // 50MB in bytes
        if (file.size > maxSize) {
            alert('El archivo excede el tamaño máximo permitido de 50MB');
            fileInput.value = '';
            return;
        }

        // Update preview
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileType.textContent = getFileExtension(file.name).toUpperCase();
        
        // Show preview
        filePreview.style.display = 'block';
        
        // Change drop zone text
        fileDropZone.innerHTML = `
            <div class="py-3">
                <div class="mb-2">
                    <i class="bi bi-file-earmark-check text-success" style="font-size: 2.5rem;"></i>
                </div>
                <h6 class="mb-1">Archivo seleccionado</h6>
                <p class="text-muted small mb-0">Haz clic para cambiar el archivo</p>
            </div>
        `;
    }

    // Remove file
    if (removeFileBtn) {
        removeFileBtn.addEventListener('click', function() {
            fileInput.value = '';
            filePreview.style.display = 'none';
            
            // Reset drop zone
            fileDropZone.innerHTML = `
                <div class="py-4">
                    <div class="mb-3">
                        <i class="bi bi-cloud-arrow-up text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="mb-2">Arrastra y suelta tu archivo aquí</h6>
                    <p class="text-muted small mb-3">o haz clic para seleccionar</p>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="browse-btn">
                        <i class="bi bi-folder2-open me-1"></i>Explorar archivos
                    </button>
                </div>
            `;
            
            // Re-attach event listeners
            const newBrowseBtn = document.getElementById('browse-btn');
            if (newBrowseBtn && fileInput) {
                newBrowseBtn.addEventListener('click', function() {
                    fileInput.click();
                });
            }
        });
    }

    // Form submission
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validate
            if (!unidadSelect.value) {
                alert('Por favor selecciona una unidad curricular');
                unidadSelect.focus();
                return;
            }

            if (!fileInput.files.length) {
                alert('Por favor selecciona un archivo');
                fileInput.click();
                return;
            }

            // Show loading state
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                Subiendo...
            `;
            submitBtn.disabled = true;

            // Simulate upload (replace with actual AJAX)
            setTimeout(() => {
                // Here you would normally do the actual form submission
                // For now, we'll just show a success message
                
                // Create success modal
                const successModal = document.createElement('div');
                successModal.className = 'modal fade show';
                successModal.style.display = 'block';
                successModal.style.backgroundColor = 'rgba(0,0,0,0.5)';
                successModal.innerHTML = `
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-body p-4 text-center">
                                <div class="mb-3">
                                    <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="mb-2">¡Trabajo Entregado!</h5>
                                <p class="text-muted mb-3">Tu trabajo ha sido subido exitosamente.</p>
                                <div class="d-flex justify-content-center gap-2">
                                    <button type="button" class="btn btn-outline-secondary" onclick="location.reload()">
                                        Subir otro
                                    </button>
                                    <button type="button" class="btn btn-primary" onclick="window.location.href='{{ url()->previous() }}'">
                                        Volver
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                document.body.appendChild(successModal);
                document.body.classList.add('modal-open');
                
                // Reset form
                uploadForm.reset();
                filePreview.style.display = 'none';
                unidadDetails.style.display = 'none';
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
            }, 1500);
        });
    }

    // Helper functions
    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function getFileExtension(filename) {
        return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
    }
});
</script>
@endpush