<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();
        
        if ($user->id_rol != $role) {
            abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return $next($request);
    }
}