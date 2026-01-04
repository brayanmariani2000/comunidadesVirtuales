<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Página de bienvenida pública
     */
    public function welcome()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }
        return view('welcome');
    }

    /**
     * Redirección según el rol del usuario
     */
    public function home()
    {
        $usuario = Auth::user();
        
        if ($usuario->esAdministrador()) {
            return redirect()->route('admin.dashboard');
        } elseif ($usuario->esProfesor()) {
            return redirect()->route('profesor.dashboard');
        } else {
            return redirect()->route('estudiante.dashboard');
        }
    }
}