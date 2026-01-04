<?php

namespace App\View\Components\Layout;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Auth;

class Sidebar extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $usuario = Auth::user();
        
        // Determinar menú según rol
        if ($usuario && $usuario->esEstudiante()) {
            return $this->renderEstudiante($usuario);
        } elseif ($usuario && $usuario->esProfesor()) {
            return $this->renderProfesor($usuario);
        } elseif ($usuario && $usuario->esAdministrador()) {
            return $this->renderAdministrador($usuario);
        }
        
        return view('components.layout.sidebar-guest');
    }
    
    private function renderEstudiante($usuario)
    {
        $menuItems = [
            [
                'titulo' => 'Dashboard',
                'icono' => 'bi-speedometer2',
                'url' => route('estudiante.dashboard'),
                'activo' => request()->routeIs('estudiante.dashboard')
            ],
            [
                'titulo' => 'Chat',
                'icono' => 'bi-chat-dots',
                'submenu' => [
                    [
                        'titulo' => 'Mis Chats',
                        'url' => route('chat.index'),
                        'activo' => request()->routeIs('chat.index')
                    ],
                    [
                        'titulo' => 'Chats de Unidad',
                        'url' => route('estudiante.chat.unidades'),
                        'activo' => request()->routeIs('estudiante.chat.unidades')
                    ]
                ]
            ],
            [
                'titulo' => 'Trabajos',
                'icono' => 'bi-file-earmark-text',
                'submenu' => [
                    [
                        'titulo' => 'Entregar Trabajo',
                        'url' => route('estudiante.trabajos.entregar'),
                        'activo' => request()->routeIs('estudiante.trabajos.entregar')
                    ],
                    [
                        'titulo' => 'Mis Entregas',
                        'url' => route('estudiante.trabajos.entregas'),
                        'activo' => request()->routeIs('estudiante.trabajos.entregas')
                    ]
                ]
            ],
            [
                'titulo' => 'Materiales',
                'icono' => 'bi-folder',
                'url' => route('estudiante.materiales'),
                'activo' => request()->routeIs('estudiante.materiales')
            ]
        ];
        
        return view('components.layout.sidebar', [
            'usuario' => $usuario,
            'menuItems' => $menuItems
        ]);
    }
    
    private function renderProfesor($usuario)
    {
        $menuItems = [
            [
                'titulo' => 'Dashboard',
                'icono' => 'bi-speedometer2',
                'url' => route('profesor.dashboard'),
                'activo' => request()->routeIs('profesor.dashboard')
            ],
            [
                'titulo' => 'Chat',
                'icono' => 'bi-chat-dots',
                'submenu' => [
                    [
                        'titulo' => 'Mis Chats',
                        'url' => route('chat.index'),
                        'activo' => request()->routeIs('chat.index')
                    ],
                    [
                        'titulo' => 'Crear Chat',
                        'url' => route('chat.create'),
                        'activo' => request()->routeIs('chat.create')
                    ]
                ]
            ],
            [
                'titulo' => 'Unidades',
                'icono' => 'bi-book',
                'url' => route('profesor.unidades'),
                'activo' => request()->routeIs('profesor.unidades')
            ],
            [
                'titulo' => 'Materiales',
                'icono' => 'bi-folder',
                'url' => route('profesor.materiales.index'),
                'activo' => request()->routeIs('profesor.materiales.*')
            ],
            [
                'titulo' => 'Estudiantes',
                'icono' => 'bi-people',
                'url' => route('profesor.estudiantes'),
                'activo' => request()->routeIs('profesor.estudiantes')
            ],
            [
                'titulo' => 'Trabajos Recibidos',
                'icono' => 'bi-file-earmark-check',
                'url' => route('profesor.trabajos'),
                'activo' => request()->routeIs('profesor.trabajos')
            ]
        ];
        
        return view('components.layout.sidebar', [
            'usuario' => $usuario,
            'menuItems' => $menuItems
        ]);
    }
    
    private function renderAdministrador($usuario)
    {
        $menuItems = [
            [
                'titulo' => 'Dashboard',
                'icono' => 'bi-speedometer2',
                'url' => route('admin.dashboard'),
                'activo' => request()->routeIs('admin.dashboard')
            ],
            [
                'titulo' => 'Usuarios',
                'icono' => 'bi-people',
                'url' => route('admin.usuarios'),
                'activo' => request()->routeIs('admin.usuarios')
            ],
            [
                'titulo' => 'Chats',
                'icono' => 'bi-chat-dots',
                'url' => route('chat.index'),
                'activo' => request()->routeIs('chat.index')
            ],
            [
                'titulo' => 'Unidades',
                'icono' => 'bi-book',
                'url' => route('admin.unidades'),
                'activo' => request()->routeIs('admin.unidades')
            ]
        ];
        
        return view('components.layout.sidebar', [
            'usuario' => $usuario,
            'menuItems' => $menuItems
        ]);
    }
}