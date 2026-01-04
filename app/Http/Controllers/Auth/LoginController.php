<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Usuario;

class LoginController extends Controller
{
    /**
     * Mostrar formulario de login
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Procesar inicio de sesión
     */
    public function login(Request $request)
    {
        // Validar credenciales
        $credentials = $request->validate([
            'correo' => 'required|email',
            'password' => 'required|string',
        ]);

        // Buscar usuario por correo y que esté activo
        $usuario = Usuario::where('correo', $credentials['correo'])
                          ->where('activo', true)
                          ->first();

        if (!$usuario) {
            return back()->withErrors([
                'correo' => 'Usuario no encontrado o cuenta inactiva.',
            ])->onlyInput('correo');
        }

        // Verificar contraseña usando Hash::check()
        if (!Hash::check($credentials['password'], $usuario->contrasena_hash)) {
            return back()->withErrors([
                'correo' => 'Las credenciales proporcionadas no son válidas.',
            ])->onlyInput('correo');
        }

        // Iniciar sesión
        Auth::login($usuario, $request->boolean('remember'));

        // Actualizar último acceso
        $usuario->ultimo_acceso = now();
        $usuario->save();

        // Registrar sesión en BD
        try {
            DB::table('sesiones_usuario')->insert([
                'id_usuario' => $usuario->id_usuario,
                'direccion_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'fecha_inicio' => now(),
                'activa' => true,
            ]);
        } catch (\Exception $e) {
            // Si falla, continuar igual
        }

        // Redirigir según rol
        return $this->redirigirSegunRol($usuario);
    }

    /**
     * Redirigir según el rol del usuario
     */
    private function redirigirSegunRol($usuario)
    {
        switch ($usuario->id_rol) {
            case 1: // Administrador
                return redirect()->route('admin.dashboard');
            case 2: // Profesor
                return redirect()->route('profesor.dashboard');
            case 3: // Estudiante
                return redirect()->route('estudiante.dashboard');
            default:
                return redirect('/login');
        }
    }

    /**
     * Cerrar sesión
     */
    public function logout(Request $request)
    {
        // Marcar sesión como inactiva
        if (Auth::check()) {
            try {
                DB::table('sesiones_usuario')
                    ->where('id_usuario', Auth::id())
                    ->where('activa', true)
                    ->update([
                        'fecha_fin' => now(),
                        'activa' => false,
                    ]);
            } catch (\Exception $e) {
                // Si falla, continuar igual
            }
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Sesión cerrada exitosamente');
    }
}