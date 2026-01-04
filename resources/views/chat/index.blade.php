@extends('layouts.app', ['bodyClass' => 'chat-list-page'])

@section('title', 'Mis Conversaciones')

@section('content')
<div class="chat-list-container">
    <!-- Mobile Header -->
    <div class="chat-mobile-header d-md-none bg-white shadow-sm py-3 px-4">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-secondary btn-sm me-3" id="mobile-sidebar-toggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h5 class="mb-0 fw-semibold">Mensajes</h5>
                    <small class="text-muted">{{ $chats->count() }} conversaciones</small>
                </div>
            </div>
            <button class="btn btn-primary btn-sm rounded-pill px-3" onclick="window.location.href='{{ route('chat.create') }}'">
                <i class="bi bi-plus-lg me-1"></i>Nuevo
            </button>
        </div>
    </div>

    <div class="row g-0 h-100">
        <!-- Sidebar de chats -->
        <aside class="col-md-4 col-lg-3 chat-sidebar" id="chat-sidebar">
            <!-- Desktop Header -->
            <div class="sidebar-header d-none d-md-block bg-white border-bottom">
                <div class="p-4">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h5 class="mb-1 fw-semibold text-dark">Conversaciones</h5>
                            <small class="text-muted">{{ $chats->count() }} activas</small>
                        </div>
                        <button class="btn btn-primary btn-sm rounded-circle" 
                                onclick="window.location.href='{{ route('chat.create') }}'"
                                title="Nuevo chat">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                    
                    <!-- Search -->
                    <div class="search-container mb-3">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar conversaciones..." id="chat-search">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Search -->
            <div class="sidebar-mobile-search d-md-none p-3 border-bottom bg-white">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-light border-end-0">
                        <i class="bi bi-search text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" 
                           placeholder="Buscar conversaciones..." id="mobile-chat-search">
                </div>
            </div>

            <!-- Chat List -->
            <div class="chat-list-wrapper" data-simplebar>
                <div class="chat-list">
                    @forelse($chats as $chat)
                    <div class="chat-item-wrapper" data-chat-name="{{ strtolower($chat->nombre_chat) }}">
                        <a href="{{ route('chat.show', $chat->id_chat) }}" 
                           class="chat-item d-flex align-items-start p-3 text-decoration-none {{ request()->route('id') == $chat->id_chat ? 'active' : '' }}">
                            <div class="position-relative me-3 flex-shrink-0">
                                <div class="chat-avatar rounded-circle d-flex align-items-center justify-content-center
                                            {{ request()->route('id') == $chat->id_chat ? 'bg-primary text-white' : 'bg-light text-dark' }}"
                                     style="width: 48px; height: 48px; font-weight: 500; font-size: 0.875rem;">
                                    {{ substr($chat->nombre_chat, 0, 2) }}
                                </div>
                                @if($chat->has_unread ?? false)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle bg-danger p-1"
                                      style="min-width: 8px; height: 8px; border: 2px solid #fff;">
                                    <span class="visually-hidden">Mensajes no leídos</span>
                                </span>
                                @endif
                            </div>
                            
                            <div class="chat-info flex-grow-1 overflow-hidden">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h6 class="mb-0 text-truncate fw-medium" 
                                        style="font-size: 0.9375rem;">
                                        {{ $chat->nombre_chat }}
                                    </h6>
                                    @if($chat->ultima_fecha)
                                    <small class="text-muted ms-2 flex-shrink-0" style="font-size: 0.75rem;">
                                        {{ \Carbon\Carbon::parse($chat->ultima_fecha)->format('H:i') }}
                                    </small>
                                    @endif
                                </div>
                                
                                <div class="chat-preview mb-1">
                                    @if($chat->ultimo_mensaje && $chat->ultimo_mensaje != 'Sin mensajes')
                                    <p class="mb-0 text-truncate" style="font-size: 0.8125rem; line-height: 1.4;">
                                        <span class="fw-medium text-dark">{{ $chat->ultimo_usuario_nombre }}:</span>
                                        <span class="text-muted">{{ Str::limit($chat->ultimo_mensaje, 45) }}</span>
                                    </p>
                                    @else
                                    <p class="mb-0 text-muted fst-italic" style="font-size: 0.8125rem;">
                                        Sin mensajes aún
                                    </p>
                                    @endif
                                </div>
                                
                                @if($chat->participants_count ?? false)
                                <div class="chat-meta">
                                    <small class="text-muted" style="font-size: 0.75rem;">
                                        <i class="bi bi-people me-1"></i>{{ $chat->participants_count }} participantes
                                    </small>
                                </div>
                                @endif
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="empty-chat-list text-center py-5">
                        <div class="mb-4">
                            <i class="bi bi-chat-dots text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                        <h6 class="text-dark mb-2">No hay conversaciones</h6>
                        <p class="text-muted small mb-4">Inicia un nuevo chat para comenzar a comunicarte</p>
                        <button class="btn btn-primary rounded-pill px-4" 
                                onclick="window.location.href='{{ route('chat.create') }}'">
                            <i class="bi bi-plus-lg me-1"></i>Crear chat
                        </button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- User Profile (Desktop) -->
            <div class="sidebar-footer d-none d-md-block border-top bg-white p-3">
                <div class="d-flex align-items-center">
                    <div class="user-avatar me-3">
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                             style="width: 36px; height: 36px; font-size: 0.875rem; font-weight: 500;">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    </div>
                    <div class="user-info flex-grow-1">
                        <h6 class="mb-0 text-dark fw-medium" style="font-size: 0.875rem;">
                            {{ Auth::user()->name }}
                        </h6>
                        <small class="text-muted">En línea</small>
                    </div>
                    <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle">
                        <i class="bi bi-gear"></i>
                    </a>
                </div>
            </div>
        </aside>

        <!-- Área principal (sin selección) -->
        <main class="col-md-8 col-lg-9 chat-welcome-area d-flex align-items-center justify-content-center">
            <div class="welcome-container text-center p-4">
                <!-- Animated Icon -->
                <div class="welcome-icon mb-4">
                    <div class="icon-wrapper position-relative">
                        <div class="outer-circle rounded-circle bg-primary bg-opacity-10 mx-auto"
                             style="width: 120px; height: 120px;">
                            <div class="inner-circle rounded-circle bg-primary bg-opacity-10 mx-auto d-flex align-items-center justify-content-center"
                                 style="width: 90px; height: 90px; margin-top: 15px;">
                                <i class="bi bi-chat-left-text-fill text-primary" style="font-size: 2.5rem;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <h2 class="h4 mb-3 fw-semibold text-dark">Bienvenido al Chat Universitario</h2>
                <p class="text-muted mb-4" style="max-width: 500px; margin: 0 auto;">
                    Conéctate con tus compañeros y profesores para colaborar en proyectos, 
                    resolver dudas y mantener la comunicación académica fluida.
                </p>
                
                <!-- Quick Actions -->
                <div class="quick-actions mb-5">
                    <div class="row g-3 justify-content-center">
                        <div class="col-auto">
                            <button class="btn btn-outline-primary d-flex align-items-center px-4 py-2"
                                    onclick="window.location.href='{{ route('chat.create') }}'">
                                <i class="bi bi-plus-circle me-2"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Nuevo Chat</div>
                                    <small class="d-block text-muted" style="font-size: 0.75rem;">Crear conversación</small>
                                </div>
                            </button>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-secondary d-flex align-items-center px-4 py-2">
                                <i class="bi bi-search me-2"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Buscar Contactos</div>
                                    <small class="d-block text-muted" style="font-size: 0.75rem;">Encontrar personas</small>
                                </div>
                            </button>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-secondary d-flex align-items-center px-4 py-2">
                                <i class="bi bi-people me-2"></i>
                                <div class="text-start">
                                    <div class="fw-medium">Grupos Activos</div>
                                    <small class="d-block text-muted" style="font-size: 0.75rem;">Ver todos</small>
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="chat-statistics">
                    <div class="row g-4 justify-content-center">
                        <div class="col-auto">
                            <div class="stat-card bg-white rounded-3 p-3 shadow-sm border text-center"
                                 style="min-width: 120px;">
                                <div class="stat-icon mb-2">
                                    <i class="bi bi-chat-square-text text-primary fs-4"></i>
                                </div>
                                <h5 class="mb-1 fw-bold text-dark">{{ $chats->count() }}</h5>
                                <small class="text-muted">Conversaciones</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="stat-card bg-white rounded-3 p-3 shadow-sm border text-center"
                                 style="min-width: 120px;">
                                <div class="stat-icon mb-2">
                                    <i class="bi bi-person-check text-success fs-4"></i>
                                </div>
                                <h5 class="mb-1 fw-bold text-dark">{{ $total_participants ?? 0 }}</h5>
                                <small class="text-muted">Contactos</small>
                            </div>
                        </div>
                        <div class="col-auto">
                            <div class="stat-card bg-white rounded-3 p-3 shadow-sm border text-center"
                                 style="min-width: 120px;">
                                <div class="stat-icon mb-2">
                                    <i class="bi bi-clock-history text-warning fs-4"></i>
                                </div>
                                <h5 class="mb-1 fw-bold text-dark">{{ $unread_messages ?? 0 }}</h5>
                                <small class="text-muted">No leídos</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Help Tip -->
                <div class="help-tip mt-5">
                    <div class="alert alert-light border d-inline-flex align-items-center px-3 py-2">
                        <i class="bi bi-lightbulb text-warning me-2"></i>
                        <small class="text-muted">
                            <strong>Consejo:</strong> Mantén tus conversaciones organizadas creando grupos por materia o proyecto.
                        </small>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>
@endsection

@push('styles')
<style>
.chat-list-page {
    background-color: #f8f9fa;
}

.chat-list-container {
    height: calc(100vh - 56px);
    overflow: hidden;
}

.chat-sidebar {
    height: 100%;
    background-color: #fff;
    border-right: 1px solid #dee2e6;
    transition: transform 0.3s ease;
    z-index: 1000;
}

.chat-welcome-area {
    background-color: #f8f9fa;
    padding: 2rem;
}

.chat-list-wrapper {
    height: calc(100vh - 260px);
}

@media (max-width: 767.98px) {
    .chat-list-wrapper {
        height: calc(100vh - 180px);
    }
    
    .chat-welcome-area {
        display: none !important;
    }
    
    .chat-sidebar {
        position: fixed;
        top: 56px;
        left: 0;
        width: 100%;
        height: calc(100vh - 56px);
        transform: translateX(-100%);
        z-index: 1050;
    }
    
    .chat-sidebar.show {
        transform: translateX(0);
    }
}

/* Chat Items */
.chat-item {
    transition: all 0.2s ease;
    border-left: 3px solid transparent;
}

.chat-item:hover {
    background-color: #f8f9fa;
}

.chat-item.active {
    background-color: #f0f7ff;
    border-left-color: #0d6efd;
    position: relative;
}

.chat-item.active::after {
    content: '';
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 6px;
    height: 6px;
    background-color: #0d6efd;
    border-radius: 50%;
}

/* Welcome Area */
.welcome-icon {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-10px);
    }
}

.outer-circle, .inner-circle {
    transition: transform 0.3s ease;
}

.outer-circle:hover {
    transform: rotate(15deg);
}

.inner-circle:hover {
    transform: rotate(-15deg);
}

/* Quick Actions */
.quick-actions .btn {
    min-width: 200px;
    text-align: left;
    border-radius: 10px;
    transition: all 0.2s ease;
}

.quick-actions .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

/* Statistics */
.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.1) !important;
}

.stat-card .stat-icon {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background-color: rgba(13, 110, 253, 0.1);
    margin: 0 auto 1rem;
}

/* Search */
.search-container .input-group {
    background-color: #f8f9fa;
    border-radius: 6px;
    padding: 0.25rem;
}

.search-container .form-control {
    background-color: transparent;
    border: none;
    box-shadow: none;
}

.search-container .form-control:focus {
    box-shadow: none;
}

/* Custom scrollbar */
.simplebar-scrollbar::before {
    background-color: rgba(0,0,0,0.2);
}

/* Empty State */
.empty-chat-list {
    padding: 3rem 1rem;
}

.empty-chat-list .btn {
    padding: 0.5rem 2rem;
}

/* Mobile Header */
.chat-mobile-header {
    position: sticky;
    top: 0;
    z-index: 1020;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar toggle
    const mobileSidebarToggle = document.getElementById('mobile-sidebar-toggle');
    const chatSidebar = document.getElementById('chat-sidebar');
    
    if (mobileSidebarToggle && chatSidebar) {
        mobileSidebarToggle.addEventListener('click', function() {
            chatSidebar.classList.toggle('show');
        });
        
        // Close sidebar when clicking on a chat link on mobile
        if (window.innerWidth < 768) {
            document.querySelectorAll('.chat-item').forEach(item => {
                item.addEventListener('click', function() {
                    chatSidebar.classList.remove('show');
                });
            });
        }
    }
    
    // Chat search functionality
    const searchInputs = ['chat-search', 'mobile-chat-search'];
    
    searchInputs.forEach(inputId => {
        const input = document.getElementById(inputId);
        if (input) {
            input.addEventListener('input', function(e) {
                const searchTerm = e.target.value.toLowerCase();
                const chatItems = document.querySelectorAll('.chat-item-wrapper');
                
                chatItems.forEach(item => {
                    const chatName = item.getAttribute('data-chat-name');
                    if (chatName.includes(searchTerm)) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }
    });
    
    // Highlight active chat on load
    const activeChat = document.querySelector('.chat-item.active');
    if (activeChat) {
        setTimeout(() => {
            activeChat.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 300);
    }
    
    // Add hover effects to quick action buttons
    document.querySelectorAll('.quick-actions .btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = '';
            this.style.boxShadow = '';
        });
    });
    
    // Auto-focus search on desktop
    if (window.innerWidth >= 768) {
        const desktopSearch = document.getElementById('chat-search');
        if (desktopSearch) {
            setTimeout(() => {
                desktopSearch.focus();
            }, 500);
        }
    }
    
    // Update online status periodically
    function updateOnlineStatus() {
        // In a real app, you would make an API call here
        const onlineStatus = document.querySelectorAll('.user-info small');
        onlineStatus.forEach(el => {
            el.textContent = 'En línea';
        });
    }
    
    // Update every 30 seconds
    setInterval(updateOnlineStatus, 30000);
});
</script>
@endpush