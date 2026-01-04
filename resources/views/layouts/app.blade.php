<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Comunidad Virtual')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-color: #4e73df;
            --dark-primary: #224abe;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fc;
        }
        
        /* SIDEBAR FIXED */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--dark-primary) 100%);
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            z-index: 1000;
            box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 0;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        /* TOGGLE BUTTON */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: var(--primary-color);
            border: none;
            color: white;
            width: 45px;
            height: 45px;
            border-radius: 8px;
            font-size: 1.5rem;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        /* NAV LINKS */
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 3px 15px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.2);
            font-weight: bold;
        }
        
        /* USER INFO */
        .user-profile {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* BADGES DE ROL */
        .role-badge {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
        }
        
        .role-admin {
            background: #dc3545;
        }
        
        .role-profesor {
            background: #17a2b8;
        }
        
        .role-estudiante {
            background: #28a745;
        }
        
        /* RESPONSIVE */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                width: 280px;
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar {
                width: 250px;
            }
            
            .sidebar .nav-link {
                padding: 10px 15px;
                margin: 2px 10px;
                font-size: 0.9rem;
            }
        }
        
        /* CONTENT STYLES */
        .content-wrapper {
            padding: 2rem;
        }
        
        @media (max-width: 768px) {
            .content-wrapper {
                padding: 1rem;
            }
        }
        
        /* CARD STYLES */
        .border-left-primary {
            border-left: 4px solid #4e73df !important;
        }
        
        .border-left-success {
            border-left: 4px solid #1cc88a !important;
        }
        
        .border-left-info {
            border-left: 4px solid #36b9cc !important;
        }
        
        .border-left-warning {
            border-left: 4px solid #f6c23e !important;
        }
        
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        
        .text-gray-300 {
            color: #dddfeb !important;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @auth
    <!-- Botón toggle para móvil -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="d-flex flex-column h-100">
            <!-- Logo -->
            <div class="user-profile">
                <div class="d-flex align-items-center">
                    <i class="bi bi-mortarboard-fill fs-3 text-white me-2"></i>
                    <div>
                        <span class="fs-5 fw-bold text-white">Comunidad</span>
                        <div>
                            @php
                                $roleClass = 'role-admin';
                                $roleText = 'Administrador';
                                if(Auth::user()->id_rol == 2) {
                                    $roleClass = 'role-profesor';
                                    $roleText = 'Profesor';
                                } elseif(Auth::user()->id_rol == 3) {
                                    $roleClass = 'role-estudiante';
                                    $roleText = 'Estudiante';
                                }
                            @endphp
                            <span class="badge {{ $roleClass }} role-badge">{{ $roleText }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Menú de navegación FILTRADO POR ROL -->
            <div class="flex-grow-1 p-3">
                <ul class="nav flex-column">
                    <!-- Dashboard para todos -->
                    <li class="nav-item">
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Inicio
                        </a>
                    </li>
                    
                    <!-- Menú solo para ADMINISTRADOR (id_rol = 1) -->
                    @if(Auth::user()->id_rol == 1)
                        <li class="nav-item">
                            <a href="{{ route('admin.usuarios') }}" class="nav-link {{ request()->routeIs('admin.usuarios*') ? 'active' : '' }}">
                                <i class="bi bi-people me-2"></i>
                                Usuarios
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.unidades') }}" class="nav-link {{ request()->routeIs('admin.unidades*') ? 'active' : '' }}">
                                <i class="bi bi-journal-text me-2"></i>
                                Unidades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.asignaciones') }}" class="nav-link {{ request()->routeIs('admin.asignaciones*') ? 'active' : '' }}">
                                <i class="bi bi-link-45deg me-2"></i>
                                Asignaciones
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.reportes') }}" class="nav-link {{ request()->routeIs('admin.reportes*') ? 'active' : '' }}">
                                <i class="bi bi-bar-chart me-2"></i>
                                Reportes
                            </a>
                        </li>
                    @endif
                    
                    <!-- Menú para PROFESOR (id_rol = 2) -->
                    @if(Auth::user()->id_rol == 2)
                        <li class="nav-item">
                            <a href="{{ route('profesor.unidades') }}" class="nav-link {{ request()->routeIs('profesor.unidades*') ? 'active' : '' }}">
                                <i class="bi bi-journal-text me-2"></i>
                                Mis Unidades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profesor.trabajos') }}" class="nav-link {{ request()->routeIs('profesor.trabajos*') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                Trabajos
                            </a>
                        </li>
                    @endif
                    
                    <!-- Menú para ESTUDIANTE (id_rol = 3) -->
                    @if(Auth::user()->id_rol == 3)
                        <li class="nav-item">
                            <a href="{{ route('estudiante.unidades') }}" class="nav-link {{ request()->routeIs('estudiante.unidades*') ? 'active' : '' }}">
                                <i class="bi bi-journal-text me-2"></i>
                                Mis Unidades
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('estudiante.trabajos') }}" class="nav-link {{ request()->routeIs('estudiante.trabajos*') ? 'active' : '' }}">
                                <i class="bi bi-file-earmark-text me-2"></i>
                                Trabajos
                            </a>
                        </li>
                    @endif
                    
                    <!-- Chat común para todos los roles -->
                    <li class="nav-item">
                        <a href="{{ route('chat.index') }}" class="nav-link {{ request()->routeIs('chat*') ? 'active' : '' }}">
                            <i class="bi bi-chat-dots me-2"></i>
                            Chat
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Perfil de usuario y logout -->
            <div class="p-3 border-top border-white-10">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown">
                        <div class="user-avatar me-2">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <strong>{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</strong>
                            <small class="text-white-50">{{ Auth::user()->correo }}</small>
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark shadow" aria-labelledby="dropdownUser">
                        <li><a class="dropdown-item" href="#">
                            <i class="bi bi-person me-2"></i>Perfil
                        </a></li>
                        <li><a class="dropdown-item" href="#">
                            <i class="bi bi-gear me-2"></i>Configuración
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="mainContent">
        <div class="content-wrapper">
            <!-- Alertas -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <!-- Contenido específico de cada página -->
            @yield('content')
        </div>
    </div>
    
    <!-- JavaScript para toggle sidebar -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    // Para móviles, oscurecer el contenido cuando se abre el sidebar
                    if (window.innerWidth <= 992) {
                        mainContent.style.opacity = sidebar.classList.contains('active') ? '0.3' : '1';
                    }
                });
            }
            
            // Cerrar sidebar al hacer clic fuera en móviles
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 992) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnToggle = sidebarToggle.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnToggle && sidebar.classList.contains('active')) {
                        sidebar.classList.remove('active');
                        mainContent.style.opacity = '1';
                    }
                }
            });
            
            // Cerrar sidebar al cambiar de tamaño de ventana
            window.addEventListener('resize', function() {
                if (window.innerWidth > 992) {
                    sidebar.classList.remove('active');
                    mainContent.style.opacity = '1';
                }
            });
        });
    </script>
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>