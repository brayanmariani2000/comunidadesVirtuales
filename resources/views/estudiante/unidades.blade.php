@extends('layouts.app')

@section('title', 'Mis Unidades Curriculares')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-2 fw-semibold text-dark">Mis Unidades Curriculares</h1>
            <p class="text-muted mb-0">Gestiona tus unidades y revisa tus trabajos entregados</p>
        </div>
        <div class="badge bg-light text-dark border px-3 py-2">
            <i class="bi bi-book me-1"></i>
            {{ $unidades->count() }} {{ $unidades->count() === 1 ? 'Unidad' : 'Unidades' }}
        </div>
    </div>

    @if($unidades->isEmpty())
    <!-- Empty State -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="mb-4">
                <i class="bi bi-journal-x text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
            </div>
            <h4 class="h5 text-muted mb-2">No estás inscrito en ninguna unidad</h4>
            <p class="text-muted mb-0">Contacta con tu coordinador para inscribirte en unidades curriculares</p>
        </div>
    </div>
    @else
    <!-- Units Grid -->
    <div class="row g-4">
        @foreach($unidades as $unidad)
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100 hover-card">
                <!-- Card Header -->
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="flex-grow-1">
                            <h5 class="mb-2 fw-semibold text-dark">{{ $unidad->nombre_unidad }}</h5>
                            @if($unidad->profesor)
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-person me-1"></i>
                                <span>{{ $unidad->profesor->nombre }} {{ $unidad->profesor->apellido }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="badge bg-primary bg-opacity-10 text-primary">
                            {{ $unidad->creditos }} créditos
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <!-- Stats Row -->
                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="fw-semibold text-primary fs-5">{{ $unidad->compañeros }}</div>
                                <small class="text-muted">Compañeros</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="fw-semibold text-success fs-5">{{ $unidad->trabajos_calificados ?? 0 }}</div>
                                <small class="text-muted">Calificados</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="text-center p-2 bg-light rounded">
                                <div class="fw-semibold text-warning fs-5">{{ $unidad->trabajos_pendientes ?? 0 }}</div>
                                <small class="text-muted">Pendientes</small>
                            </div>
                        </div>
                    </div>

                    <!-- Trabajos Entregados Section -->
                    @if(isset($unidad->trabajos_entregados) && $unidad->trabajos_entregados->count() > 0)
                    <div class="trabajos-section">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <h6 class="mb-0 fw-semibold text-dark">
                                <i class="bi bi-file-earmark-check me-1"></i>
                                Trabajos Entregados
                            </h6>
                            <span class="badge bg-light text-dark border">
                                {{ $unidad->trabajos_entregados->count() }}
                            </span>
                        </div>
                        
                        <div class="trabajos-list">
                            @foreach($unidad->trabajos_entregados->take(3) as $trabajo)
                            <div class="trabajo-item border rounded p-2 mb-2 bg-light">
                                <div class="d-flex align-items-start">
                                    <div class="me-2">
                                        @if($trabajo->calificacion)
                                        <i class="bi bi-check-circle-fill text-success"></i>
                                        @else
                                        <i class="bi bi-clock text-warning"></i>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0 text-truncate small fw-medium">
                                                {{ $trabajo->titulo ?? $trabajo->nombre_archivo }}
                                            </h6>
                                            @if($trabajo->calificacion)
                                            <span class="badge bg-success ms-2">{{ $trabajo->calificacion }}</span>
                                            @else
                                            <span class="badge bg-warning ms-2">Pendiente</span>
                                            @endif
                                        </div>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            {{ \Carbon\Carbon::parse($trabajo->fecha_entrega)->format('d/m/Y H:i') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            
                            @if($unidad->trabajos_entregados->count() > 3)
                            <div class="text-center mt-2">
                                <small class="text-muted">
                                    + {{ $unidad->trabajos_entregados->count() - 3 }} trabajo(s) más
                                </small>
                            </div>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="alert alert-light border mb-0">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle text-muted me-2"></i>
                            <small class="text-muted mb-0">No has entregado trabajos en esta unidad</small>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-white border-top">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            @if($unidad->chats && $unidad->chats->count() > 0)
                            <a href="{{ route('chat.show', $unidad->chats->first()->id_chat) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-chat-dots me-1"></i>Chat
                            </a>
                            @endif
                            <a href="{{ route('estudiante.trabajos') }}" 
                               class="btn btn-sm btn-outline-success">
                                <i class="bi bi-upload me-1"></i>Entregar
                            </a>
                        </div>
                        <button class="btn btn-sm btn-link text-muted" 
                                type="button" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#details-{{ $unidad->id_unidad }}">
                            <i class="bi bi-info-circle me-1"></i>Detalles
                        </button>
                    </div>
                    
                    <!-- Collapsible Details -->
                    <div class="collapse mt-3" id="details-{{ $unidad->id_unidad }}">
                        <div class="border-top pt-3">
                            <div class="row g-2 small">
                                @if($unidad->descripcion)
                                <div class="col-12">
                                    <strong class="text-dark">Descripción:</strong>
                                    <p class="text-muted mb-2">{{ $unidad->descripcion }}</p>
                                </div>
                                @endif
                                <div class="col-6">
                                    <strong class="text-dark">Código:</strong>
                                    <p class="text-muted mb-0">{{ $unidad->codigo_unidad }}</p>
                                </div>
                                <div class="col-6">
                                    <strong class="text-dark">Inscrito:</strong>
                                    <p class="text-muted mb-0">
                                        {{ \Carbon\Carbon::parse($unidad->fecha_inscripcion)->format('d/m/Y') }}
                                    </p>
                                </div>
                                @if($unidad->profesor && $unidad->profesor->correo)
                                <div class="col-12">
                                    <strong class="text-dark">Contacto:</strong>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-envelope me-1"></i>
                                        <a href="mailto:{{ $unidad->profesor->correo }}">{{ $unidad->profesor->correo }}</a>
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.hover-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,.1) !important;
}

.trabajo-item {
    transition: all 0.2s ease;
}

.trabajo-item:hover {
    background-color: #e9ecef !important;
    border-color: #0d6efd !important;
}

.trabajos-list {
    max-height: 300px;
    overflow-y: auto;
}

.trabajos-list::-webkit-scrollbar {
    width: 4px;
}

.trabajos-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.trabajos-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.trabajos-list::-webkit-scrollbar-thumb:hover {
    background: #555;
}

.badge.bg-primary.bg-opacity-10 {
    padding: 0.35rem 0.75rem;
}
</style>
@endpush
