<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BitacoraSistema;

class EstudianteController extends Controller
{
    public function dashboard()
    {
        $usuario = Auth::user();
        
        // Obtener unidades del estudiante
        $unidades = DB::table('inscripciones_estudiantes as ie')
            ->join('unidades_curriculares as uc', 'ie.id_unidad', '=', 'uc.id_unidad')
            ->leftJoin('chats as c', function($join) {
                $join->on('uc.id_unidad', '=', 'c.id_unidad')
                     ->where('c.tipo_chat', '=', 'unidad_curricular');
            })
            ->where('ie.id_estudiante', $usuario->id_usuario)
            ->where('ie.estado', 'activo')
            ->select('uc.*', 'ie.fecha_inscripcion', 'c.id_chat')
            ->get();

        // Para cada unidad, intentar obtener su profesor titular
        foreach ($unidades as $unidad) {
            $unidad->profesor = DB::table('asignacion_profesores as ap')
                ->join('usuarios as u', 'ap.id_profesor', '=', 'u.id_usuario')
                ->where('ap.id_unidad', $unidad->id_unidad)
                ->where('ap.rol_profesor', 'titular')
                ->where('ap.activo', true)
                ->select('u.nombre', 'u.apellido', 'u.correo')
                ->first();
        }

        // Obtener lista única de profesores
        $profesores = DB::table('inscripciones_estudiantes as ie')
            ->join('asignacion_profesores as ap', 'ie.id_unidad', '=', 'ap.id_unidad')
            ->join('usuarios as u', 'ap.id_profesor', '=', 'u.id_usuario')
            ->where('ie.id_estudiante', $usuario->id_usuario)
            ->where('ie.estado', 'activo')
            ->where('ap.activo', true)
            ->select('u.id_usuario', 'u.nombre', 'u.apellido', 'u.correo')
            ->distinct()
            ->get();

        // Conteo de chats activos
        $chatsActivos = DB::table('participantes_chat')
            ->where('id_usuario', $usuario->id_usuario)
            ->where('activo', true)
            ->count();

        // Mensajes sin leer (Sigue en 0 por falta de tabla mensajes_leidos)
        $mensajesSinLeer = 0;

        // Mensajes recientes del estudiante
        $mensajesRecientes = DB::table('mensajes as m')
            ->join('chats as c', 'm.id_chat', '=', 'c.id_chat')
            ->join('participantes_chat as pc', 'c.id_chat', '=', 'pc.id_chat')
            ->join('usuarios as u', 'm.id_usuario', '=', 'u.id_usuario')
            ->where('pc.id_usuario', $usuario->id_usuario)
            ->where('pc.activo', true)
            ->select('m.*', 'c.nombre_chat', 'u.nombre', 'u.apellido')
            ->orderBy('m.fecha_envio', 'desc')
            ->limit(5)
            ->get();

        foreach ($mensajesRecientes as $mensaje) {
            $mensaje->usuario = (object)[
                'nombre' => $mensaje->nombre,
                'apellido' => $mensaje->apellido
            ];
            $mensaje->chat = (object)[
                'nombre_chat' => $mensaje->nombre_chat
            ];
        }

        // Mantener las estadísticas para compatibilidad (si se siguen usando en algún lado)
        $estadisticas = [
            'unidades' => count($unidades),
            'chats' => $chatsActivos,
            'trabajos_pendientes' => 0,
            'mensajes_no_leidos' => $mensajesSinLeer,
        ];
        
        return view('estudiante.dashboard', compact(
            'estadisticas', 
            'unidades', 
            'profesores', 
            'chatsActivos', 
            'mensajesSinLeer', 
            'mensajesRecientes'
        ));
    }
    
    public function unidades()
    {
        $usuario = Auth::user();
        
        $unidades = DB::table('inscripciones_estudiantes as ie')
            ->join('unidades_curriculares as uc', 'ie.id_unidad', '=', 'uc.id_unidad')
            ->where('ie.id_estudiante', $usuario->id_usuario)
            ->where('ie.estado', 'activo')
            ->select('uc.*', 'ie.fecha_inscripcion')
            ->get();
        
        // Para cada unidad, obtener información adicional
        foreach ($unidades as $unidad) {
            // Obtener profesor de la unidad
            $profesor = DB::table('asignacion_profesores as ap')
                ->join('usuarios as u', 'ap.id_profesor', '=', 'u.id_usuario')
                ->where('ap.id_unidad', $unidad->id_unidad)
                ->where('ap.rol_profesor', 'titular')
                ->select('u.nombre', 'u.apellido', 'u.correo')
                ->first();
            
            $unidad->profesor = $profesor;
            
            // Obtener chats de la unidad
            $unidad->chats = DB::table('chats')
                ->where('id_unidad', $unidad->id_unidad)
                ->where('activo', true)
                ->get();
            
            // Contar compañeros
            $unidad->compañeros = DB::table('inscripciones_estudiantes')
                ->where('id_unidad', $unidad->id_unidad)
                ->where('id_estudiante', '!=', $usuario->id_usuario)
                ->where('estado', 'activo')
                ->count();
            
            // Obtener trabajos entregados en esta unidad
            $unidad->trabajos_entregados = DB::table('entregas_trabajos')
                ->where('id_unidad', $unidad->id_unidad)
                ->where('id_estudiante', $usuario->id_usuario)
                ->orderBy('fecha_entrega', 'desc')
                ->get();
            
            // Contar trabajos por estado
            $unidad->trabajos_pendientes = DB::table('entregas_trabajos')
                ->where('id_unidad', $unidad->id_unidad)
                ->where('id_estudiante', $usuario->id_usuario)
                ->where('estado', 'pendiente')
                ->count();
                
            $unidad->trabajos_calificados = DB::table('entregas_trabajos')
                ->where('id_unidad', $unidad->id_unidad)
                ->where('id_estudiante', $usuario->id_usuario)
                ->whereNotNull('calificacion')
                ->count();
        }
        
        return view('estudiante.unidades', compact('unidades'));
    }
    
    // Método chatUnidades eliminado por redundancia y falta de vista. Usar ChatController@index.
    
    public function entregarTrabajo(Request $request)
    {
        $userId = Auth::id();
        $tareaId = $request->query('tarea_id');
        $tarea = null;
        
        if ($tareaId) {
            $tarea = DB::table('tareas as t')
                ->join('chats as c', 't.id_chat', '=', 'c.id_chat')
                ->where('t.id_tarea', $tareaId)
                ->select('t.*', 'c.id_unidad')
                ->first();
        }

        $unidades = DB::table('inscripciones_estudiantes as ie')
            ->join('unidades_curriculares as uc', 'ie.id_unidad', '=', 'uc.id_unidad')
            ->where('ie.id_estudiante', function($query) use ($userId) {
                $query->select('id_estudiante')
                    ->from('estudiantes')
                    ->where('id_estudiante', $userId) // El PK de estudiantes es el id_usuario
                    ->limit(1);
            })
            ->select('uc.*')
            ->get();
        
        $tareasPendientes = [];
        
        return view('estudiante.trabajos.entregar', compact('unidades', 'tareasPendientes', 'tarea'));
    }
    
    public function entregarTrabajoStore(Request $request)
    {
        $request->validate([
            'id_unidad' => 'required|exists:unidades_curriculares,id_unidad',
            'id_tarea' => 'nullable|exists:tareas,id_tarea',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'archivo' => 'required|file|max:10240',
        ]);
        
        $usuario = Auth::user();
        $idEstudiante = DB::table('estudiantes')->where('id_estudiante', $usuario->id_usuario)->value('id_estudiante');
        
        if (!$idEstudiante) {
            return back()->with('error', 'No se encontró el registro de estudiante.');
        }
        
        // Guardar el archivo
        $archivo = $request->file('archivo');
        $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
        $ruta = $archivo->storeAs('trabajos_estudiantes', $nombreArchivo);
        
        // Registrar la entrega en la base de datos
        DB::table('entregas_trabajos')->insert([
            'id_estudiante' => $idEstudiante,
            'id_unidad' => $request->id_unidad,
            'id_tarea' => $request->id_tarea,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'nombre_archivo' => $archivo->getClientOriginalName(),
            'ruta_archivo' => $ruta,
            'estado' => 'pendiente',
            'fecha_entrega' => now()
        ]);

        // Registrar en bitácora
        BitacoraSistema::registrar(
            'Entrega de Trabajo',
            'Estudiante/Académico',
            "El estudiante entregó el trabajo: {$request->titulo}"
        );
        
        return redirect()->route('estudiante.trabajos.entregas')
            ->with('success', 'Trabajo entregado correctamente.');
    }
    
    public function misEntregas()
    {
        $usuario = Auth::user();
        
        $entregas = DB::table('entregas_trabajos as et')
            ->join('unidades_curriculares as uc', 'et.id_unidad', '=', 'uc.id_unidad')
            ->where('et.id_estudiante', $usuario->id_usuario)
            ->select('et.*', 'uc.nombre_unidad')
            ->orderBy('et.fecha_entrega', 'desc')
            ->get();
        
        return view('estudiante.trabajos.entregas', compact('entregas'));
    }
    
    public function calificacionesTrabajos()
    {
        $usuario = Auth::user();
        
        // Obtener entregas con calificaciones
        $calificaciones = DB::table('entregas_trabajos as et')
            ->join('unidades_curriculares as uc', 'et.id_unidad', '=', 'uc.id_unidad')
            ->where('et.id_estudiante', $usuario->id_usuario)
            ->whereNotNull('et.calificacion')
            ->select('et.*', 'uc.nombre_unidad')
            ->orderBy('et.fecha_entrega', 'desc')
            ->get();
        
        return view('estudiante.trabajos.calificaciones', compact('calificaciones'));
    }
    
    public function materiales()
    {
        $usuario = Auth::user();
        
        // Obtener materiales de las unidades del estudiante
        $materiales = DB::table('documentos_adjuntos as da')
            ->join('mensajes as m', 'da.id_mensaje', '=', 'm.id_mensaje')
            ->join('chats as c', 'm.id_chat', '=', 'c.id_chat')
            ->join('unidades_curriculares as uc', 'c.id_unidad', '=', 'uc.id_unidad')
            ->join('inscripciones_estudiantes as ie', function($join) use ($usuario) {
                $join->on('uc.id_unidad', '=', 'ie.id_unidad')
                     ->where('ie.id_estudiante', $usuario->id_usuario);
            })
            ->whereIn('da.tipo_archivo', [
                'application/pdf', 
                'application/msword',
                'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                'application/vnd.ms-excel',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'application/vnd.ms-powerpoint',
                'application/vnd.openxmlformats-officedocument.presentationml.presentation'
            ])
            ->select('da.*', 'uc.nombre_unidad', 'c.nombre_chat', 'm.fecha_envio')
            ->orderBy('m.fecha_envio', 'desc')
            ->get();
        
        return view('estudiante.materiales', compact('materiales'));
    }
    public function trabajos()
    {
        $usuario = Auth::user();
        
        $unidades = DB::table('inscripciones_estudiantes as ie')
            ->join('unidades_curriculares as uc', 'ie.id_unidad', '=', 'uc.id_unidad')
            ->where('ie.id_estudiante', $usuario->id_usuario)
            ->where('ie.estado', 'activo')
            ->select('uc.*')
            ->get();
            
        return view('estudiante.trabajos', compact('unidades'));
    }
}