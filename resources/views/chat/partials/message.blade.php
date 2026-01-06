@php
    $isOwn = $mensaje->id_usuario == Auth::id();
    // Note: $showAvatar logic might need to be passed from the parent or simplified for real-time
    $showAvatar = $showAvatar ?? true; 
@endphp

<div class="message-row d-flex mb-3 {{ $isOwn ? 'justify-content-end' : 'justify-content-start' }}" data-message-id="{{ $mensaje->id_mensaje }}">
    @if(!$isOwn && $showAvatar)
    <div class="message-avatar me-2">
        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
             style="width: 32px; height: 32px; font-size: 0.75rem;">
            {{ substr($mensaje->usuario_nombre, 0, 1) }}
        </div>
    </div>
    @elseif(!$isOwn)
    <div class="message-avatar-placeholder" style="width: 32px; margin-right: 8px;"></div>
    @endif
    
    <div class="message-content" style="max-width: 75%;">
        @if(!$isOwn && $showAvatar)
        <div class="message-sender mb-1">
            <small class="text-dark fw-medium">{{ $mensaje->usuario_nombre }} {{ $mensaje->usuario_apellido }}</small>
            @if(isset($mensaje->usuario_rol) && $mensaje->usuario_rol)
            <small class="text-muted ms-1">• {{ $mensaje->usuario_rol }}</small>
            @endif
        </div>
        @endif
        
        <div class="message-bubble {{ $isOwn ? 'bg-primary text-white' : 'bg-white' }} rounded-3 shadow-sm p-3">
            <div class="message-text mb-2" style="line-height: 1.5;">
                @if($mensaje->tipo_mensaje === 'tarea')
                    @php $tarea = json_decode($mensaje->contenido_texto); @endphp
                    <div class="tarea-card border border-primary rounded p-3 bg-light">
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-journal-check text-primary fs-4 me-2"></i>
                            <h6 class="mb-0 fw-bold text-dark">NUEVA TAREA</h6>
                        </div>
                        <h5 class="h6 mb-2 text-dark">{{ $tarea->titulo }}</h5>
                        <p class="small text-muted mb-3">{{ $tarea->descripcion }}...</p>
                        <div class="d-grid">
                            @if(Auth::user()->esEstudiante())
                            <a href="{{ route('estudiante.trabajos.entregar', ['tarea_id' => $tarea->id_tarea]) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="bi bi-upload me-1"></i>Entregar Aquí
                            </a>
                            @else
                            <a href="{{ route('profesor.trabajos') }}" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye me-1"></i>Ver Entregas
                            </a>
                            @endif
                        </div>
                    </div>
                @else
                    {{ $mensaje->contenido_texto }}
                @endif
            </div>
            
            @if(isset($mensaje->nombre_archivo) && $mensaje->nombre_archivo)
            <div class="message-attachment mt-2">
                <div class="border rounded p-2 bg-light">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            @php
                                $ext = pathinfo($mensaje->nombre_archivo, PATHINFO_EXTENSION);
                                $icon = match($ext) {
                                    'pdf' => 'bi-file-earmark-pdf text-danger',
                                    'doc', 'docx' => 'bi-file-earmark-word text-primary',
                                    'xls', 'xlsx' => 'bi-file-earmark-excel text-success',
                                    'jpg', 'jpeg', 'png', 'gif' => 'bi-file-earmark-image text-info',
                                    default => 'bi-file-earmark-text text-secondary'
                                };
                            @endphp
                            <i class="bi {{ $icon }} fs-4"></i>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="text-truncate mb-1">
                                <strong class="text-dark">{{ $mensaje->nombre_archivo }}</strong>
                            </div>
                            <div class="d-flex align-items-center">
                                <small class="text-muted me-3">{{ round($mensaje->tamano_archivo / 1024) }} KB</small>
                                <small class="text-muted">{{ strtoupper($ext) }}</small>
                            </div>
                        </div>
                        <div class="ms-3">
                            <a href="{{ asset('storage/' . $mensaje->ruta_almacenamiento) }}" 
                               class="btn btn-sm btn-outline-secondary" 
                               target="_blank"
                               download>
                                <i class="bi bi-download"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            
            <div class="message-footer d-flex justify-content-between align-items-center mt-2">
                <small class="{{ $isOwn ? 'text-white-75' : 'text-muted' }}" 
                       style="font-size: 0.75rem;">
                    {{ \Carbon\Carbon::parse($mensaje->fecha_envio)->format('H:i') }}
                </small>
            </div>
        </div>
    </div>
</div>
