@extends('layouts.app')

@section('title', 'Materiales de Estudio')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-folder2-open me-2"></i>Materiales de Estudio
        </h1>
    </div>

    @if($materiales->count() > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Documentos Compartidos</h6>
            <span class="badge bg-primary">{{ $materiales->count() }} archivos</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Archivo</th>
                            <th>Unidad</th>
                            <th>Chat</th>
                            <th>Fecha</th>
                            <th>Tamaño</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($materiales as $material)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    @php
                                        $ext = pathinfo($material->nombre_archivo, PATHINFO_EXTENSION);
                                        $iconClass = match($ext) {
                                            'pdf' => 'bi-file-earmark-pdf text-danger',
                                            'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                            'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                            'ppt', 'pptx' => 'bi-file-earmark-slides text-warning',
                                            default => 'bi-file-earmark-text text-secondary'
                                        };
                                    @endphp
                                    <i class="bi {{ $iconClass }} fs-3 me-3"></i>
                                    <div>
                                        <div class="fw-bold">{{ $material->nombre_archivo }}</div>
                                        <small class="text-muted">{{ strtoupper($ext) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $material->nombre_unidad }}</span>
                            </td>
                            <td>
                                <small>{{ $material->nombre_chat }}</small>
                            </td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($material->fecha_envio)->format('d/m/Y') }}</small>
                            </td>
                            <td>
                                <small>{{ round($material->tamano_archivo / 1024, 1) }} KB</small>
                            </td>
                            <td>
                                <a href="{{ asset('storage/' . $material->ruta_almacenamiento) }}" 
                                   class="btn btn-sm btn-primary" 
                                   target="_blank"
                                   download>
                                    <i class="bi bi-download me-1"></i>Descargar
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <!-- Estado vacío -->
    <div class="card shadow">
        <div class="card-body text-center py-5">
            <i class="bi bi-folder2-open fs-1 text-muted mb-3 d-block"></i>
            <h5 class="text-muted mb-3">No hay materiales disponibles</h5>
            <p class="text-muted mb-0">Los documentos compartidos por tus profesores aparecerán aquí</p>
        </div>
    </div>
    @endif
</div>
@endsection
