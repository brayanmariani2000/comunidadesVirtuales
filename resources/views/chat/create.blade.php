@extends('layouts.app')

@section('title', 'Crear Nuevo Chat')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>Crear Nuevo Chat
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('chat.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="nombre_chat" class="form-label">
                            <i class="bi bi-chat-left-text me-1"></i>Nombre del Chat
                        </label>
                        <input type="text" 
                               class="form-control" 
                               id="nombre_chat" 
                               name="nombre_chat" 
                               placeholder="Ej: Grupo de Estudio - Programación I"
                               required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            <i class="bi bi-card-text me-1"></i>Descripción (Opcional)
                        </label>
                        <textarea class="form-control" 
                                  id="descripcion" 
                                  name="descripcion" 
                                  rows="3"
                                  placeholder="Describe el propósito de este chat..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tipo_chat" class="form-label">
                            <i class="bi bi-gear me-1"></i>Tipo de Chat
                        </label>
                        <select class="form-select" id="tipo_chat" name="tipo_chat" required>
                            <option value="">Selecciona un tipo</option>
                            <option value="privado">Chat Privado</option>
                            <option value="grupal">Chat Grupal</option>
                            <option value="unidad_curricular">Chat de Unidad Curricular</option>
                        </select>
                    </div>
                    
                    <div class="mb-3" id="unidad-container" style="display: none;">
                        <label for="id_unidad" class="form-label">
                            <i class="bi bi-journal-text me-1"></i>Unidad Curricular
                        </label>
                        <select class="form-select" id="id_unidad" name="id_unidad">
                            <option value="">Selecciona una unidad</option>
                            @foreach($unidades as $unidad)
                                <option value="{{ $unidad->id_unidad }}">
                                    {{ $unidad->codigo_unidad }} - {{ $unidad->nombre_unidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('chat.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Cancelar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-2"></i>Crear Chat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Mostrar/ocultar selector de unidad según el tipo de chat
    document.getElementById('tipo_chat').addEventListener('change', function() {
        const unidadContainer = document.getElementById('unidad-container');
        if (this.value === 'unidad_curricular') {
            unidadContainer.style.display = 'block';
            document.getElementById('id_unidad').required = true;
        } else {
            unidadContainer.style.display = 'none';
            document.getElementById('id_unidad').required = false;
        }
    });
</script>
@endpush
@endsection