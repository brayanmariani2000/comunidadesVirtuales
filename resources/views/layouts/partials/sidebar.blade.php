@php
    $usuario = auth()->user();
    $menuItems = [];
    
    if ($usuario) {
        if ($usuario->esAdministrador()) {
            $menuItems = [
                ['titulo' => 'Dashboard', 'icono' => 'bi-speedometer2', 'url' => route('admin.dashboard')],
                ['titulo' => 'Usuarios', 'icono' => 'bi-people', 'url' => route('admin.usuarios')],
                // ... resto del menú administrador
            ];
        } elseif ($usuario->esProfesor()) {
            $menuItems = [
                ['titulo' => 'Dashboard', 'icono' => 'bi-speedometer2', 'url' => route('profesor.dashboard')],
                // ... resto del menú profesor
            ];
        } elseif ($usuario->esEstudiante()) {
            $menuItems = [
                ['titulo' => 'Dashboard', 'icono' => 'bi-speedometer2', 'url' => route('estudiante.dashboard')],
                // ... resto del menú estudiante
            ];
        }
    }
@endphp

<nav id="sidebar" class="sidebar">
    <!-- Código del sidebar usando $usuario y $menuItems -->
</nav>