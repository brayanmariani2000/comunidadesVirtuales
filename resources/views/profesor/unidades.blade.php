@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Unidades Curriculares que imparto</h2>
        <a href="{{ route('profesor.unidades.create.lote') }}" class="btn btn-success">
            <i class="bi bi-file-earmark-pdf me-2"></i>Crear Unidad por Lote (PDF)
        </a>
    </div>
    <div class="row">
        @forelse($unidades as $unidad)
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $unidad->codigo_unidad }} - {{ $unidad->nombre_unidad }}</h5>
                        <p class="card-text">{{ $unidad->descripcion }}</p>
                        <p>Créditos: {{ $unidad->creditos }}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <a href="#" class="btn btn-primary">Ver detalles</a>
                            <form action="{{ route('profesor.unidades.destroy', $unidad->id_unidad) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar esta unidad? Se borrará el chat y todas las inscripciones asociadas.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>No tienes unidades asignadas.</p>
        @endforelse
    </div>
</div>
@endsection