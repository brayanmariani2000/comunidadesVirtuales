@extends('layouts.app')

@section('title', 'Reportes del Sistema')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="bi bi-bar-chart me-2"></i>Reportes y Estadísticas
        </h1>
    </div>

    <div class="row">
        <!-- Usuarios por Rol -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Usuarios por Rol</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Rol</th>
                                    <th>Total</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalUsuarios = $usuariosPorRol->sum('total'); @endphp
                                @foreach($usuariosPorRol as $rol)
                                <tr>
                                    <td>{{ $rol->nombre_rol }}</td>
                                    <td><strong>{{ $rol->total }}</strong></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ ($rol->total / $totalUsuarios) * 100 }}%">
                                                {{ round(($rol->total / $totalUsuarios) * 100, 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unidades Más Populares -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Unidades Más Populares</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Unidad</th>
                                    <th>Estudiantes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($unidadesPopulares as $unidad)
                                <tr>
                                    <td><strong>{{ $unidad->codigo_unidad }}</strong></td>
                                    <td>{{ $unidad->nombre_unidad }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ $unidad->total_estudiantes }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No hay datos disponibles</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actividad del Chat -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actividad de Chat (Últimos 6 Meses)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Mes</th>
                                    <th>Total Mensajes</th>
                                    <th>Gráfico</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($actividadChat as $actividad)
                                <tr>
                                    <td>{{ $actividad->mes }}</td>
                                    <td><strong>{{ $actividad->total }}</strong></td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-info" role="progressbar" 
                                                 style="width: {{ ($actividad->total / ($actividadChat->max('total') ?: 1)) * 100 }}%">
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">No hay datos disponibles</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
