@extends('layouts.app')

@section('title', $chat->nombre_chat . ' - Chat')

@section('content')
<div class="chat-container">
    <!-- Sidebar Mobile Toggle -->
    <button class="sidebar-toggle-btn btn btn-light btn-sm d-md-none rounded-circle shadow-sm" id="sidebar-toggle">
        <i class="bi bi-list"></i>
    </button>

    <div class="row g-0 h-100">
        <!-- Sidebar de chats -->
        <aside class="col-md-4 col-lg-3 chat-sidebar" id="chat-sidebar">
            <div class="sidebar-header border-bottom bg-white">
                <div class="p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="mb-0 text-dark fw-semibold">Conversaciones</h6>
                        <a href="{{ route('chat.create') }}" class="btn btn-primary btn-sm rounded-pill">
                            <i class="bi bi-plus-lg"></i>
                        </a>
                    </div>
                    
                    <div class="search-container">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-transparent border-end-0">
                                <i class="bi bi-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" 
                                   placeholder="Buscar chats..." id="chat-search">
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Lista de chats -->
            <div class="chat-list-container" data-simplebar>
                @foreach($chats as $c)
                <div class="chat-item-wrapper" data-chat-name="{{ strtolower($c->nombre_chat) }}">
                    <a href="{{ route('chat.show', $c->id_chat) }}" 
                       class="chat-item d-flex align-items-center p-3 text-decoration-none {{ $c->id_chat == $chat->id_chat ? 'active' : '' }}"
                       data-chat-id="{{ $c->id_chat }}">
                        <div class="position-relative me-3">
                            <div class="chat-avatar rounded-circle d-flex align-items-center justify-content-center 
                                        {{ $c->id_chat == $chat->id_chat ? 'bg-primary text-white' : 'bg-light text-dark' }}"
                                 style="width: 42px; height: 42px; font-size: 0.875rem; font-weight: 500;">
                                {{ substr($c->nombre_chat, 0, 2) }}
                            </div>
                            @if($c->has_unread ?? false)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem; padding: 0.2rem 0.3rem;">
                                <span class="visually-hidden">Mensajes no leídos</span>
                            </span>
                            @endif
                        </div>
                        
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 text-truncate {{ $c->id_chat == $chat->id_chat ? 'text-primary' : 'text-dark' }}" 
                                    style="font-size: 0.875rem; font-weight: 500;">
                                    {{ $c->nombre_chat }}
                                </h6>
                                @if($c->ultima_fecha)
                                <small class="text-muted ms-2 flex-shrink-0" style="font-size: 0.75rem;">
                                    {{ \Carbon\Carbon::parse($c->ultima_fecha)->format('H:i') }}
                                </small>
                                @endif
                            </div>
                            
                            <div class="chat-preview">
                                @if($c->ultimo_mensaje && $c->ultimo_mensaje != 'Sin mensajes')
                                <p class="mb-0 text-truncate" style="font-size: 0.8125rem; line-height: 1.3;">
                                    <span class="text-muted fw-medium">{{ $c->ultimo_usuario_nombre }}:</span>
                                    <span class="text-muted">{{ Str::limit($c->ultimo_mensaje, 35) }}</span>
                                </p>
                                @else
                                <p class="mb-0 text-muted fst-italic" style="font-size: 0.8125rem;">
                                    Sin mensajes
                                </p>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            </div>
        </aside>

        <!-- Área principal del chat -->
        <main class="col-md-8 col-lg-9 chat-main-area">
            <!-- Chat Header -->
            <header class="chat-header bg-white border-bottom">
                <div class="container-fluid">
                    <div class="d-flex align-items-center justify-content-between py-3">
                        <div class="d-flex align-items-center">
                            <div class="current-chat-avatar me-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                     style="width: 44px; height: 44px; font-weight: 500;">
                                    {{ substr($chat->nombre_chat, 0, 2) }}
                                </div>
                            </div>
                            <div>
                                <h1 class="h5 mb-1 fw-semibold text-dark">{{ $chat->nombre_chat }}</h1>
                                @if($chat->descripcion)
                                <p class="text-muted mb-0 small">{{ $chat->descripcion }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-people me-1"></i>
                                    {{ $participantes->count() }}
                                </span>
                            </div>
                            @if(Auth::user()->esProfesor())
                            <div class="me-3">
                                <button class="btn btn-primary btn-sm rounded-pill px-3" type="button" 
                                        data-bs-toggle="modal" data-bs-target="#modalNuevaTarea">
                                    <i class="bi bi-journal-plus me-1"></i>
                                    Nueva Tarea
                                </button>
                            </div>
                            @endif
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-three-dots"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" style="min-width: 180px;">
                                    <li>
                                        <a class="dropdown-item py-2" href="#">
                                            <i class="bi bi-info-circle me-2"></i>Información
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="#">
                                            <i class="bi bi-pin-angle me-2"></i>Fijar chat
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item py-2 text-danger" href="#">
                                            <i class="bi bi-trash me-2"></i>Eliminar
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Mensajes -->
            <section class="chat-messages-container bg-body-tertiary" data-simplebar id="messages-container">
                <div class="container-fluid py-4">
                    @if($mensajes->count() > 0)
                    <div class="date-separator text-center mb-4">
                        <span class="badge bg-light text-muted border px-3 py-1" style="font-weight: 400; font-size: 0.8125rem;">
                            Hoy
                        </span>
                    </div>
                    @endif
                    
                    <div class="chat-messages" id="messages-list">
                        @forelse($mensajes as $index => $mensaje)
                            @php
                                $isOwn = $mensaje->id_usuario == Auth::id();
                                $showAvatar = !$isOwn && 
                                    ($index === 0 || 
                                     $mensajes[$index-1]->id_usuario != $mensaje->id_usuario ||
                                     \Carbon\Carbon::parse($mensaje->fecha_envio)->diffInMinutes(\Carbon\Carbon::parse($mensajes[$index-1]->fecha_envio)) > 5);
                            @endphp
                            @include('chat.partials.message', ['mensaje' => $mensaje, 'showAvatar' => $showAvatar])
                        @empty
                        <div class="empty-chat-state text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-chat-square-text text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                            </div>
                            <h4 class="h6 text-muted mb-2">No hay mensajes aún</h4>
                            <p class="text-muted small mb-0">Envía el primer mensaje para comenzar la conversación</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </section>

            <!-- Input de mensaje -->
            <footer class="chat-input-container bg-white border-top">
                <div class="container-fluid py-3">
                    <form action="{{ route('chat.mensaje.enviar', $chat->id_chat) }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          id="message-form">
                        @csrf
                        
                        <div class="message-input-wrapper position-relative">
                            <!-- Attachment preview -->
                            <div class="attachment-preview mb-2" id="attachment-preview" style="display: none;"></div>
                            
                            <div class="d-flex align-items-center">
                                <!-- Hidden file input -->
                                <input type="file" 
                                       name="archivo" 
                                       id="file-input" 
                                       class="d-none" 
                                       accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif">
                                
                                <!-- Attachment button -->
                                <button type="button" class="btn btn-light rounded-circle me-2" id="attach-btn"
                                        style="width: 40px; height: 40px;">
                                    <i class="bi bi-paperclip"></i>
                                </button>
                                
                                <!-- Message input -->
                                <div class="flex-grow-1 position-relative">
                                    <textarea name="contenido" 
                                              class="form-control border-0 bg-light rounded-pill ps-3 pe-5" 
                                              placeholder="Escribe un mensaje..." 
                                              rows="1"
                                              id="message-input"
                                              style="resize: none; min-height: 40px; padding-right: 3rem;"
                                              oninput="autoResize(this)"></textarea>
                                    
                                    <!-- Emoji button -->
                                    <button type="button" class="btn btn-link position-absolute top-50 end-0 translate-middle-y me-3"
                                            id="emoji-btn"
                                            style="padding: 0;">
                                        <i class="bi bi-emoji-smile text-muted"></i>
                                    </button>
                                </div>
                                
                                <!-- Send button -->
                                <button class="btn btn-primary rounded-circle ms-2" 
                                        type="submit"
                                        id="send-btn"
                                        style="width: 40px; height: 40px;">
                                    <i class="bi bi-send"></i>
                                </button>
                            </div>
                            
                            <!-- Typing indicator -->
                            <div class="typing-indicator mt-1" id="typing-indicator" style="display: none;">
                                <small class="text-muted">
                                    <i class="bi bi-pencil me-1"></i>
                                    <span>Alguien está escribiendo...</span>
                                </small>
                            </div>
                        </div>
                    </form>
                </div>
            </footer>
        </main>
    </div>
</div>

<!-- Simple Emoji Picker -->
<div class="emoji-picker position-fixed bg-white border rounded shadow-sm p-2" 
     id="emoji-picker" 
     style="display: none; z-index: 1060;">
    <div class="emoji-grid" style="display: grid; grid-template-columns: repeat(8, 1fr); gap: 4px;">
        @php
            $emojis = ['😀', '😂', '🥰', '😎', '😊', '🤔', '😢', '😠', '👍', '👎', '❤️', '🔥', '🎉', '🙏', '👏', '💯'];
        @endphp
        @foreach($emojis as $emoji)
        <button type="button" class="btn btn-sm btn-outline-light border-0" 
                style="font-size: 1.25rem; padding: 0.25rem;" 
                data-emoji="{{ $emoji }}">
            {{ $emoji }}
        </button>
        @endforeach
    </div>
</div>
@if(Auth::user()->esProfesor())
<!-- Modal Nueva Tarea -->
<div class="modal fade" id="modalNuevaTarea" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-journal-plus me-2"></i>Publicar Nueva Tarea
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('chat.tarea.publicar', $chat->id_chat) }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Título de la Tarea</label>
                        <input type="text" name="titulo" class="form-control" placeholder="Ej: Trabajo Final de Módulo" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Descripción / Instrucciones</label>
                        <textarea name="descripcion" class="form-control" rows="4" placeholder="Describe brevemente lo que se debe entregar..." required></textarea>
                    </div>
                    <div class="mb-0">
                        <label class="form-label fw-semibold">Fecha Límite (Opcional)</label>
                        <input type="datetime-local" name="fecha_limite" class="form-control">
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-link text-muted" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">
                        <i class="bi bi-send me-2"></i>Publicar en el Chat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-resize textarea
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = textarea.scrollHeight + 'px';
    }
    
    // Scroll to bottom of messages
    function scrollToBottom() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
    }
    
    // Initialize
    scrollToBottom();
    
    // Auto-resize on input
    const messageInput = document.getElementById('message-input');
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            autoResize(this);
        });
        
        // Submit on Enter (without Shift)
        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    }

    const messageForm = document.getElementById('message-form');
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            sendMessage();
        });
    }

    function sendMessage() {
        if (!messageInput.value.trim() && !fileInput.files.length) return;

        const formData = new FormData(messageForm);
        const sendBtn = document.getElementById('send-btn');
        const originalBtnContent = sendBtn.innerHTML;

        sendBtn.disabled = true;
        sendBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';

        fetch(messageForm.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const messagesList = document.getElementById('messages-list');
                const emptyState = document.querySelector('.empty-chat-state');
                if (emptyState) emptyState.remove();

                messagesList.insertAdjacentHTML('beforeend', data.html);
                messageInput.value = '';
                autoResize(messageInput);
                clearAttachment();
                scrollToBottom();
                
                // Update lastMessageId
                lastMessageId = data.id_mensaje;
            }
        })
        .catch(error => console.error('Error:', error))
        .finally(() => {
            sendBtn.disabled = false;
            sendBtn.innerHTML = originalBtnContent;
        });
    }

    // Polling for new messages
    let lastMessageId = @if($mensajes->count() > 0) {{ $mensajes->last()->id_mensaje }} @else 0 @endif;
    const chatId = {{ $chat->id_chat }};

    function pollMessages() {
        fetch(`/chat/${chatId}/mensajes/nuevos?last_id=${lastMessageId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.count > 0) {
                const messagesList = document.getElementById('messages-list');
                const emptyState = document.querySelector('.empty-chat-state');
                if (emptyState) emptyState.remove();

                messagesList.insertAdjacentHTML('beforeend', data.html);
                lastMessageId = data.last_id;
                scrollToBottom();
            }
        })
        .catch(error => console.error('Error polling:', error));
    }

    // Start polling every 3 seconds
    setInterval(pollMessages, 3000);
    
    // File attachment handling
    const fileInput = document.getElementById('file-input');
    const attachBtn = document.getElementById('attach-btn');
    const attachmentPreview = document.getElementById('attachment-preview');
    
    if (attachBtn && fileInput) {
        attachBtn.addEventListener('click', function() {
            fileInput.click();
        });
    }
    
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            if (e.target.files.length > 0) {
                const file = e.target.files[0];
                showAttachmentPreview(file);
            }
        });
    }
    
    function showAttachmentPreview(file) {
        attachmentPreview.innerHTML = `
            <div class="d-flex align-items-center bg-light rounded p-2 border">
                <div class="me-3">
                    <i class="bi bi-file-earmark-text fs-4 text-primary"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="text-truncate mb-1">
                        <strong>${file.name}</strong>
                    </div>
                    <small class="text-muted">${(file.size / 1024).toFixed(1)} KB</small>
                </div>
                <button type="button" class="btn btn-sm btn-link text-danger ms-2" onclick="clearAttachment()">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        `;
        attachmentPreview.style.display = 'block';
    }
    
    window.clearAttachment = function() {
        fileInput.value = '';
        attachmentPreview.innerHTML = '';
        attachmentPreview.style.display = 'none';
    };
    
    // Emoji picker
    const emojiBtn = document.getElementById('emoji-btn');
    const emojiPicker = document.getElementById('emoji-picker');
    
    if (emojiBtn && emojiPicker) {
        emojiBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const rect = emojiBtn.getBoundingClientRect();
            emojiPicker.style.display = 'block';
            emojiPicker.style.top = (rect.top - emojiPicker.offsetHeight - 10) + 'px';
            emojiPicker.style.left = (rect.left) + 'px';
        });
        
        // Close emoji picker when clicking outside
        document.addEventListener('click', function() {
            emojiPicker.style.display = 'none';
        });
        
        // Prevent emoji picker from closing when clicking inside
        emojiPicker.addEventListener('click', function(e) {
            e.stopPropagation();
        });
        
        // Insert emoji
        emojiPicker.querySelectorAll('[data-emoji]').forEach(btn => {
            btn.addEventListener('click', function() {
                const emoji = this.getAttribute('data-emoji');
                const input = document.getElementById('message-input');
                const start = input.selectionStart;
                const end = input.selectionEnd;
                input.value = input.value.substring(0, start) + emoji + input.value.substring(end);
                input.focus();
                input.selectionStart = input.selectionEnd = start + emoji.length;
                input.dispatchEvent(new Event('input'));
                emojiPicker.style.display = 'none';
            });
        });
    }
    
    // Sidebar toggle for mobile
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const chatSidebar = document.getElementById('chat-sidebar');
    
    if (sidebarToggle && chatSidebar) {
        sidebarToggle.addEventListener('click', function() {
            chatSidebar.classList.toggle('show');
        });
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(e) {
            if (window.innerWidth < 768 && 
                !chatSidebar.contains(e.target) && 
                !sidebarToggle.contains(e.target) &&
                chatSidebar.classList.contains('show')) {
                chatSidebar.classList.remove('show');
            }
        });
    }
    
    // Chat search
    const chatSearch = document.getElementById('chat-search');
    if (chatSearch) {
        chatSearch.addEventListener('input', function(e) {
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
    
    // Typing indicator simulation (for demo)
    let typingTimeout;
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            // Simulate someone else typing
            const typingIndicator = document.getElementById('typing-indicator');
            if (typingIndicator) {
                typingIndicator.style.display = 'block';
                
                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(function() {
                    typingIndicator.style.display = 'none';
                }, 2000);
            }
        });
    }
});
</script>

<style>
.chat-container {
    height: calc(100vh - 56px);
    background-color: #f8f9fa;
}

.chat-sidebar {
    height: 100%;
    border-right: 1px solid #dee2e6;
    background-color: #fff;
    transition: transform 0.3s ease;
    z-index: 1000;
}

.chat-main-area {
    display: flex;
    flex-direction: column;
    height: 100%;
    background-color: #f8f9fa;
}

.chat-header {
    flex-shrink: 0;
    background-color: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.chat-messages-container {
    flex: 1;
    overflow: hidden;
    background-color: #f8f9fa;
}

.chat-input-container {
    flex-shrink: 0;
    background-color: #fff;
    box-shadow: 0 -1px 3px rgba(0,0,0,0.05);
}

.chat-list-container {
    height: calc(100vh - 180px);
    overflow-y: auto;
}

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
}

.chat-item.active .chat-avatar {
    background-color: #0d6efd !important;
    color: white !important;
}

.message-bubble {
    border: 1px solid rgba(0,0,0,0.05);
}

.message-bubble.bg-primary {
    border-color: rgba(255,255,255,0.1);
}

.message-row:last-child {
    margin-bottom: 0;
}

/* Simplebar custom scrollbar */
.simplebar-scrollbar::before {
    background-color: rgba(0,0,0,0.2);
}

/* Mobile sidebar */
@media (max-width: 767.98px) {
    .chat-sidebar {
        position: fixed;
        top: 56px;
        left: 0;
        width: 280px;
        height: calc(100vh - 56px);
        transform: translateX(-100%);
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
    }
    
    .chat-sidebar.show {
        transform: translateX(0);
    }
    
    .sidebar-toggle-btn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        z-index: 999;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    }
    
    .chat-messages-container {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }
}

/* Desktop adjustments */
@media (min-width: 768px) {
    .chat-container {
        height: calc(100vh - 56px);
    }
    
    .sidebar-toggle-btn {
        display: none;
    }
}

/* Message animations */
@keyframes messageAppear {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-row {
    animation: messageAppear 0.3s ease forwards;
}

/* Custom scrollbar for messages */
.chat-messages-container::-webkit-scrollbar {
    width: 6px;
}

.chat-messages-container::-webkit-scrollbar-track {
    background: transparent;
}

.chat-messages-container::-webkit-scrollbar-thumb {
    background-color: rgba(0,0,0,0.1);
    border-radius: 3px;
}

/* Focus states */
.message-input:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.1) !important;
    background-color: #fff !important;
}

/* Attachment preview animation */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-5px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.attachment-preview > div {
    animation: slideIn 0.2s ease;
}

/* Emoji picker styles */
.emoji-picker {
    box-shadow: 0 4px 20px rgba(0,0,0,0.1) !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
}

.emoji-grid button:hover {
    background-color: #f8f9fa !important;
    transform: scale(1.1);
    transition: transform 0.1s ease;
}
</style>
@endpush