<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Dashboard principal del administrador
     */
    public function tablero()
    {
        $estadisticas = [
            'total_usuarios' => DB::table('usuarios')->where('activo', true)->count(),
            'total_profesores' => DB::table('usuarios')->where('id_rol', 2)->where('activo', true)->count(),
            'total_estudiantes' => DB::table('usuarios')->where('id_rol', 3)->where('activo', true)->count(),
            'total_unidades' => DB::table('unidades_curriculares')->where('activo', true)->count(),
            'total_chats' => DB::table('chats')->where('activo', true)->count(),
            'total_mensajes_hoy' => DB::table('mensajes')->whereDate('fecha_envio', today())->count(),
            'total_inscripciones_activas' => DB::table('inscripciones_estudiantes')->where('estado', 'activo')->count(),
        ];

        // Actividad reciente
        $actividad_reciente = DB::table('bitacora_sistema as b')
            ->leftJoin('usuarios as u', 'b.id_usuario', '=', 'u.id_usuario')
            ->select('b.*', 'u.nombre', 'u.apellido')
            ->orderBy('b.fecha_hora', 'desc')
            ->limit(10)
            ->get();

        // Usuarios recientes
        $usuarios_recientes = DB::table('usuarios as u')
            ->join('roles as r', 'u.id_rol', '=', 'r.id_rol')
            ->select('u.*', 'r.nombre_rol')
            ->orderBy('u.fecha_registro', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('estadisticas', 'actividad_reciente', 'usuarios_recientes'));
    }

    /**
     * Listar todos los usuarios
     */
    public function usuarios(Request $request)
    {
        $query = DB::table('usuarios as u')
            ->join('roles as r', 'u.id_rol', '=', 'r.id_rol')
            ->select('u.*', 'r.nombre_rol');

        // Filtro por rol
        if ($request->filled('rol')) {
            $query->where('u.id_rol', $request->rol);
        }

        // Búsqueda
        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $query->where(function($q) use ($busqueda) {
                $q->where('u.nombre', 'LIKE', "%{$busqueda}%")
                  ->orWhere('u.apellido', 'LIKE', "%{$busqueda}%")
                  ->orWhere('u.correo', 'LIKE', "%{$busqueda}%")
                  ->orWhere('u.cedula', 'LIKE', "%{$busqueda}%");
            });
        }

        $usuarios = $query->orderBy('u.fecha_registro', 'desc')->paginate(15);
        $roles = DB::table('roles')->where('activo', true)->get();

        return view('admin.usuarios.listar', compact('usuarios', 'roles'));
    }

    /**
     * Mostrar formulario para crear usuario
     */
    public function crearUsuario()
    {
        $roles = DB::table('roles')->where('activo', true)->get();
        return view('admin.usuarios.crear', compact('roles'));
    }

    /**
     * Guardar nuevo usuario
     */
    public function guardarUsuario(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|string|max:20|unique:usuarios,cedula',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'required|email|max:150|unique:usuarios,correo',
            'contrasena' => 'required|string|min:6',
            'id_rol' => 'required|exists:roles,id_rol',
        ], [
            'nombre.required' => 'El nombre es obligatorio',
            'apellido.required' => 'El apellido es obligatorio',
            'cedula.required' => 'La cédula es obligatoria',
            'cedula.unique' => 'Esta cédula ya está registrada',
            'correo.required' => 'El correo es obligatorio',
            'correo.email' => 'El correo debe ser válido',
            'correo.unique' => 'Este correo ya está registrado',
            'contrasena.required' => 'La contraseña es obligatoria',
            'contrasena.min' => 'La contraseña debe tener al menos 6 caracteres',
            'id_rol.required' => 'El rol es obligatorio',
        ]);

        DB::beginTransaction();
        try {
            // Crear usuario
            $idUsuario = DB::table('usuarios')->insertGetId([
                'id_rol' => $request->id_rol,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'cedula' => $request->cedula,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'contrasena_hash' => Hash::make($request->contrasena),
                'activo' => true,
                'fecha_registro' => now(),
            ]);

            // Si es profesor, crear registro en tabla profesores
            if ($request->id_rol == 2) {
                DB::table('profesores')->insert([
                    'id_profesor' => $idUsuario,
                    'codigo_profesor' => 'PROF' . str_pad($idUsuario, 4, '0', STR_PAD_LEFT),
                    'especialidad' => $request->especialidad ?? null,
                    'grado_academico' => $request->grado_academico ?? null,
                    'fecha_contratacion' => $request->fecha_contratacion ?? now(),
                    'fecha_actualizacion' => now(),
                ]);
            }

            // Si es estudiante, crear registro en tabla estudiantes
            if ($request->id_rol == 3) {
                DB::table('estudiantes')->insert([
                    'id_estudiante' => $idUsuario,
                    'codigo_estudiante' => 'EST' . str_pad($idUsuario, 4, '0', STR_PAD_LEFT),
                    'fecha_ingreso' => $request->fecha_ingreso ?? now(),
                    'carrera' => $request->carrera ?? null,
                    'semestre_actual' => $request->semestre_actual ?? 1,
                    'fecha_actualizacion' => now(),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.usuarios')
                ->with('success', 'Usuario creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al crear usuario: ' . $e->getMessage());
        }
    }

    /**
     * Mostrar formulario para editar usuario
     */
    public function editarUsuario($id)
    {
        $usuario = DB::table('usuarios')->where('id_usuario', $id)->first();
        
        if (!$usuario) {
            abort(404, 'Usuario no encontrado');
        }

        $roles = DB::table('roles')->where('activo', true)->get();
        
        // Obtener datos adicionales según el rol
        $datosAdicionales = null;
        if ($usuario->id_rol == 2) {
            $datosAdicionales = DB::table('profesores')->where('id_profesor', $id)->first();
        } elseif ($usuario->id_rol == 3) {
            $datosAdicionales = DB::table('estudiantes')->where('id_estudiante', $id)->first();
        }

        return view('admin.usuarios.editar', compact('usuario', 'roles', 'datosAdicionales'));
    }

    /**
     * Actualizar usuario existente
     */
    public function actualizarUsuario(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellido' => 'required|string|max:100',
            'cedula' => 'required|string|max:20|unique:usuarios,cedula,' . $id . ',id_usuario',
            'telefono' => 'nullable|string|max:20',
            'correo' => 'required|email|max:150|unique:usuarios,correo,' . $id . ',id_usuario',
            'contrasena' => 'nullable|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $datosActualizar = [
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'cedula' => $request->cedula,
                'telefono' => $request->telefono,
                'correo' => $request->correo,
                'activo' => $request->has('activo'),
            ];

            // Si se proporciona nueva contraseña
            if ($request->filled('contrasena')) {
                $datosActualizar['contrasena_hash'] = Hash::make($request->contrasena);
            }

            DB::table('usuarios')->where('id_usuario', $id)->update($datosActualizar);

            // Actualizar datos adicionales según rol
            $usuario = DB::table('usuarios')->where('id_usuario', $id)->first();
            
            if ($usuario->id_rol == 2) {
                DB::table('profesores')->updateOrInsert(
                    ['id_profesor' => $id],
                    [
                        'especialidad' => $request->especialidad,
                        'grado_academico' => $request->grado_academico,
                        'fecha_actualizacion' => now(),
                    ]
                );
            } elseif ($usuario->id_rol == 3) {
                DB::table('estudiantes')->updateOrInsert(
                    ['id_estudiante' => $id],
                    [
                        'carrera' => $request->carrera,
                        'semestre_actual' => $request->semestre_actual,
                        'fecha_actualizacion' => now(),
                    ]
                );
            }

            DB::commit();

            return redirect()->route('admin.usuarios')
                ->with('success', 'Usuario actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar (desactivar) usuario
     */
    public function eliminarUsuario($id)
    {
        DB::table('usuarios')->where('id_usuario', $id)->update(['activo' => false]);
        
        return redirect()->route('admin.usuarios')
            ->with('success', 'Usuario desactivado exitosamente');
    }

    /**
     * Listar unidades curriculares
     */
    public function unidadesCurriculares(Request $request)
    {
        $query = DB::table('unidades_curriculares');

        if ($request->filled('busqueda')) {
            $busqueda = $request->busqueda;
            $query->where(function($q) use ($busqueda) {
                $q->where('nombre_unidad', 'LIKE', "%{$busqueda}%")
                  ->orWhere('codigo_unidad', 'LIKE', "%{$busqueda}%");
            });
        }

        if ($request->filled('periodo')) {
            $query->where('periodo_academico', $request->periodo);
        }

        $unidades = $query->orderBy('fecha_creacion', 'desc')->paginate(15);

        // Añadir estadísticas a cada unidad
        foreach ($unidades as $unidad) {
            $unidad->total_profesores = DB::table('asignacion_profesores')
                ->where('id_unidad', $unidad->id_unidad)
                ->where('activo', true)
                ->count();
            
            $unidad->total_estudiantes = DB::table('inscripciones_estudiantes')
                ->where('id_unidad', $unidad->id_unidad)
                ->where('estado', 'activo')
                ->count();
        }

        return view('admin.unidades.listar', compact('unidades'));
    }

    /**
     * Mostrar formulario para crear unidad
     */
    public function crearUnidad()
    {
        return view('admin.unidades.crear');
    }

    /**
     * Guardar nueva unidad curricular
     */
    public function guardarUnidad(Request $request)
    {
        $request->validate([
            'codigo_unidad' => 'required|string|max:20|unique:unidades_curriculares,codigo_unidad',
            'nombre_unidad' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:0',
            'horas_semanales' => 'required|integer|min:0',
            'periodo_academico' => 'required|string|max:50',
        ], [
            'codigo_unidad.required' => 'El código es obligatorio',
            'codigo_unidad.unique' => 'Este código ya existe',
            'nombre_unidad.required' => 'El nombre es obligatorio',
            'creditos.required' => 'Los créditos son obligatorios',
            'horas_semanales.required' => 'Las horas semanales son obligatorias',
            'periodo_academico.required' => 'El período académico es obligatorio',
        ]);

        DB::table('unidades_curriculares')->insert([
            'codigo_unidad' => $request->codigo_unidad,
            'nombre_unidad' => $request->nombre_unidad,
            'descripcion' => $request->descripcion,
            'creditos' => $request->creditos,
            'horas_semanales' => $request->horas_semanales,
            'periodo_academico' => $request->periodo_academico,
            'activo' => true,
            'fecha_creacion' => now(),
            'fecha_actualizacion' => now(),
        ]);

        return redirect()->route('admin.unidades')
            ->with('success', 'Unidad curricular creada exitosamente');
    }

    /**
     * Mostrar formulario para editar unidad
     */
    public function editarUnidad($id)
    {
        $unidad = DB::table('unidades_curriculares')->where('id_unidad', $id)->first();
        
        if (!$unidad) {
            abort(404, 'Unidad no encontrada');
        }

        return view('admin.unidades.editar', compact('unidad'));
    }

    /**
     * Actualizar unidad curricular
     */
    public function actualizarUnidad(Request $request, $id)
    {
        $request->validate([
            'codigo_unidad' => 'required|string|max:20|unique:unidades_curriculares,codigo_unidad,' . $id . ',id_unidad',
            'nombre_unidad' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'creditos' => 'required|integer|min:0',
            'horas_semanales' => 'required|integer|min:0',
            'periodo_academico' => 'required|string|max:50',
        ]);

        DB::table('unidades_curriculares')->where('id_unidad', $id)->update([
            'codigo_unidad' => $request->codigo_unidad,
            'nombre_unidad' => $request->nombre_unidad,
            'descripcion' => $request->descripcion,
            'creditos' => $request->creditos,
            'horas_semanales' => $request->horas_semanales,
            'periodo_academico' => $request->periodo_academico,
            'activo' => $request->has('activo'),
            'fecha_actualizacion' => now(),
        ]);

        return redirect()->route('admin.unidades')
            ->with('success', 'Unidad curricular actualizada exitosamente');
    }

    /**
     * Eliminar (desactivar) unidad curricular
     */
    public function eliminarUnidad($id)
    {
        DB::table('unidades_curriculares')->where('id_unidad', $id)->update(['activo' => false]);
        
        return redirect()->route('admin.unidades')
            ->with('success', 'Unidad curricular desactivada exitosamente');
    }

    /**
     * Gestionar asignaciones profesor-unidad
     */
    public function asignaciones()
    {
        $asignaciones = DB::table('asignacion_profesores as ap')
            ->join('profesores as p', 'ap.id_profesor', '=', 'p.id_profesor')
            ->join('usuarios as u', 'p.id_profesor', '=', 'u.id_usuario')
            ->join('unidades_curriculares as uc', 'ap.id_unidad', '=', 'uc.id_unidad')
            ->select('ap.*', 'u.nombre', 'u.apellido', 'uc.nombre_unidad', 'uc.codigo_un idad')
            ->where('ap.activo', true)
            ->orderBy('ap.fecha_asignacion', 'desc')
            ->get();

        $profesores = DB::table('profesores as p')
            ->join('usuarios as u', 'p.id_profesor', '=', 'u.id_usuario')
            ->where('u.activo', true)
            ->select('p.id_profesor', 'u.nombre', 'u.apellido')
            ->get();

        $unidades = DB::table('unidades_curriculares')
            ->where('activo', true)
            ->get();

        return view('admin.asignaciones', compact('asignaciones', 'profesores', 'unidades'));
    }

    /**
     * Guardar nueva asignación profesor-unidad
     */
    public function guardarAsignacion(Request $request)
    {
        $request->validate([
            'id_profesor' => 'required|exists:profesores,id_profesor',
            'id_unidad' => 'required|exists:unidades_curriculares,id_unidad',
            'rol_profesor' => 'required|in:titular,asistente,auxiliar',
        ]);

        DB::table('asignacion_profesores')->insert([
            'id_profesor' => $request->id_profesor,
            'id_unidad' => $request->id_unidad,
            'rol_profesor' => $request->rol_profesor,
            'fecha_asignacion' => now(),
            'activo' => true,
        ]);

        return back()->with('success', 'Asignación creada exitosamente');
    }

    /**
     * Gestionar inscripciones estudiante-unidad
     */
    public function inscripciones()
    {
        $inscripciones = DB::table('inscripciones_estudiantes as ie')
            ->join('estudiantes as e', 'ie.id_estudiante', '=', 'e.id_estudiante')
            ->join('usuarios as u', 'e.id_estudiante', '=', 'u.id_usuario')
            ->join('unidades_curriculares as uc', 'ie.id_unidad', '=', 'uc.id_unidad')
            ->select('ie.*', 'u.nombre', 'u.apellido', 'e.codigo_estudiante', 'uc.nombre_unidad', 'uc.codigo_unidad')
            ->orderBy('ie.fecha_inscripcion', 'desc')
            ->paginate(20);

        $estudiantes = DB::table('estudiantes as e')
            ->join('usuarios as u', 'e.id_estudiante', '=', 'u.id_usuario')
            ->where('u.activo', true)
            ->select('e.id_estudiante', 'u.nombre', 'u.apellido', 'e.codigo_estudiante')
            ->get();

        $unidades = DB::table('unidades_curriculares')
            ->where('activo', true)
            ->get();

        return view('admin.inscripciones', compact('inscripciones', 'estudiantes', 'unidades'));
    }

    /**
     * Guardar nueva inscripción estudiante-unidad
     */
    public function guardarInscripcion(Request $request)
    {
        $request->validate([
            'id_estudiante' => 'required|exists:estudiantes,id_estudiante',
            'id_unidad' => 'required|exists:unidades_curriculares,id_unidad',
        ]);

        // Verificar si ya existe la inscripción
        $existe = DB::table('inscripciones_estudiantes')
            ->where('id_estudiante', $request->id_estudiante)
            ->where('id_unidad', $request->id_unidad)
            ->exists();

        if ($existe) {
            return back()->with('error', 'El estudiante ya está inscrito en esta unidad');
        }

        DB::table('inscripciones_estudiantes')->insert([
            'id_estudiante' => $request->id_estudiante,
            'id_unidad' => $request->id_unidad,
            'estado' => 'activo',
            'fecha_inscripcion' => now(),
            'fecha_actualizacion' => now(),
        ]);

        return back()->with('success', 'Inscripción creada exitosamente');
    }

    /**
     * Mostrar reportes del sistema
     */
    public function reportes()
    {
        // Reporte de usuarios por rol
        $usuariosPorRol = DB::table('usuarios as u')
            ->join('roles as r', 'u.id_rol', '=', 'r.id_rol')
            ->where('u.activo', true)
            ->select('r.nombre_rol', DB::raw('COUNT(*) as total'))
            ->groupBy('r.nombre_rol')
            ->get();

        // Unidades más populares
        $unidadesPopulares = DB::table('unidades_curriculares as uc')
            ->leftJoin('inscripciones_estudiantes as ie', 'uc.id_unidad', '=', 'ie.id_unidad')
            ->select('uc.nombre_unidad', 'uc.codigo_unidad', DB::raw('COUNT(ie.id_inscripcion) as total_estudiantes'))
            ->where('uc.activo', true)
            ->groupBy('uc.id_unidad', 'uc.nombre_unidad', 'uc.codigo_unidad')
            ->orderByDesc('total_estudiantes')
            ->limit(10)
            ->get();

        // Actividad de chat por mes
        $actividadChat = DB::table('mensajes')
            ->select(DB::raw('DATE_FORMAT(fecha_envio, "%Y-%m") as mes'), DB::raw('COUNT(*) as total'))
            ->groupBy('mes')
            ->orderBy('mes', 'desc')
            ->limit(6)
            ->get();

        return view('admin.reportes', compact('usuariosPorRol', 'unidadesPopulares', 'actividadChat'));
    }
}
