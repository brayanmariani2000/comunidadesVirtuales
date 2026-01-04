<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\BitacoraSistema;

class ProfesorController extends Controller
{
    public function dashboard()
    {
        $usuario = Auth::user();
        $idProfesor = $usuario->id_usuario; // id_profesor ES id_usuario
        
        // Obtener estadísticas del profesor
        $estadisticas = [
            'unidades' => DB::table('asignacion_profesores')
                ->where('id_profesor', $idProfesor)
                ->count(),
            'estudiantes' => DB::table('inscripciones_estudiantes as ie')
                ->join('asignacion_profesores as ap', 'ie.id_unidad', '=', 'ap.id_unidad')
                ->where('ap.id_profesor', $idProfesor)
                ->distinct('ie.id_estudiante')
                ->count('ie.id_estudiante'),
            'chats' => DB::table('chats')
                ->where('creado_por', $usuario->id_usuario)
                ->orWhereExists(function ($query) use ($usuario) {
                    $query->select(DB::raw(1))
                        ->from('participantes_chat')
                        ->whereColumn('participantes_chat.id_chat', 'chats.id_chat')
                        ->where('participantes_chat.id_usuario', $usuario->id_usuario);
                })
                ->count()
        ];
        
        // Obtener unidades del profesor
        $unidades = DB::table('asignacion_profesores as ap')
            ->join('unidades_curriculares as uc', 'ap.id_unidad', '=', 'uc.id_unidad')
            ->where('ap.id_profesor', $idProfesor)
            ->select('uc.*', 'ap.rol_profesor')
            ->get();
        
        // Obtener últimos mensajes
        $ultimosMensajes = DB::table('mensajes as m')
            ->join('chats as c', 'm.id_chat', '=', 'c.id_chat')
            ->join('usuarios as u', 'm.id_usuario', '=', 'u.id_usuario')
            ->whereExists(function ($query) use ($usuario) {
                $query->select(DB::raw(1))
                    ->from('participantes_chat as pc')
                    ->whereColumn('pc.id_chat', 'c.id_chat')
                    ->where('pc.id_usuario', $usuario->id_usuario)
                    ->where('pc.activo', true);
            })
            ->select('m.*', 'c.nombre_chat', 'u.nombre', 'u.apellido')
            ->orderBy('m.fecha_envio', 'desc')
            ->limit(5)
            ->get();
        
        return view('profesor.dashboard', compact('estadisticas', 'unidades', 'ultimosMensajes'));
    }
    
    public function unidades()
    {
        $usuario = Auth::user();
        $idProfesor = $usuario->id_usuario;
        
        $unidades = DB::table('asignacion_profesores as ap')
            ->join('unidades_curriculares as uc', 'ap.id_unidad', '=', 'uc.id_unidad')
            ->where('ap.id_profesor', $idProfesor)
            ->select('uc.*', 'ap.rol_profesor', 'ap.fecha_asignacion')
            ->get();
        
        // Para cada unidad, obtener número de estudiantes
        foreach ($unidades as $unidad) {
            $unidad->estudiantes_count = DB::table('inscripciones_estudiantes')
                ->where('id_unidad', $unidad->id_unidad)
                ->where('estado', 'activo')
                ->count();
            
            $unidad->chats_count = DB::table('chats')
                ->where('id_unidad', $unidad->id_unidad)
                ->where('activo', true)
                ->count();
        }
        
        return view('profesor.unidades', compact('unidades'));
    }
    
    // Método chatPrivado eliminado por código muerto/falta de vista. Usar ChatController@index.
    
    public function materialesIndex()
    {
        $usuario = Auth::user();
        
        $materiales = DB::table('documentos_adjuntos as da')
            ->join('mensajes as m', 'da.id_mensaje', '=', 'm.id_mensaje')
            ->join('chats as c', 'm.id_chat', '=', 'c.id_chat')
            ->where('m.id_usuario', $usuario->id_usuario)
            ->whereIn('da.tipo_archivo', ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
            ->select('da.*', 'c.nombre_chat', 'm.fecha_envio')
            ->orderBy('m.fecha_envio', 'desc')
            ->get();
        
        return view('profesor.materiales.index', compact('materiales'));
    }
    
    public function materialesSubir()
    {
        $usuario = Auth::user();
        $idProfesor = $usuario->id_usuario;
        
        $unidades = DB::table('asignacion_profesores as ap')
            ->join('unidades_curriculares as uc', 'ap.id_unidad', '=', 'uc.id_unidad')
            ->where('ap.id_profesor', $idProfesor)
            ->select('uc.*')
            ->get();
        
        $chats = DB::table('chats as c')
            ->join('participantes_chat as pc', 'c.id_chat', '=', 'pc.id_chat')
            ->where('pc.id_usuario', $usuario->id_usuario)
            ->where('pc.activo', true)
            ->select('c.*')
            ->get();
        
        return view('profesor.materiales.subir', compact('unidades', 'chats'));
    }
    
    public function estudiantes()
    {
        $usuario = Auth::user();
        $idProfesor = $usuario->id_usuario;
        
        $estudiantes = DB::table('inscripciones_estudiantes as ie')
            ->join('asignacion_profesores as ap', 'ie.id_unidad', '=', 'ap.id_unidad')
            ->join('usuarios as u', 'ie.id_estudiante', '=', 'u.id_usuario')
            ->join('unidades_curriculares as uc', 'ie.id_unidad', '=', 'uc.id_unidad')
            ->where('ap.id_profesor', $idProfesor)
            ->where('ie.estado', 'activo')
            ->select('u.*', 'uc.nombre_unidad', 'ie.fecha_inscripcion')
            ->get();
        
        return view('profesor.estudiantes', compact('estudiantes'));
    }
    
    public function calificaciones()
    {
        return view('profesor.calificaciones');
    }
    public function trabajos(Request $request)
    {
        $profesorId = Auth::id();
        $tareaId = $request->query('id_tarea');

        $query = DB::table('entregas_trabajos as et')
            ->join('estudiantes as est', 'et.id_estudiante', '=', 'est.id_estudiante')
            ->join('usuarios as u_est', 'est.id_estudiante', '=', 'u_est.id_usuario')
            ->join('unidades_curriculares as uc', 'et.id_unidad', '=', 'uc.id_unidad')
            ->join('asignacion_profesores as ap', 'uc.id_unidad', '=', 'ap.id_unidad')
            ->leftJoin('tareas as t', 'et.id_tarea', '=', 't.id_tarea')
            ->where('ap.id_profesor', $profesorId)
            ->where('ap.activo', true)
            ->select(
                'et.*', 
                'u_est.nombre as nombre_estudiante', 
                'u_est.apellido as apellido_estudiante', 
                'u_est.correo', 
                'uc.nombre_unidad',
                't.titulo as titulo_tarea'
            )
            ->orderBy('et.fecha_entrega', 'desc');

        if ($tareaId) {
            $query->where('et.id_tarea', $tareaId);
        }

        $entregas = $query->get();

        return view('profesor.trabajos', compact('entregas'));
    }

    // Métodos para crear unidad por lote (PDF)
    public function createUnidadLote()
    {
        return view('profesor.crear-unidad-lote');
    }

    public function storeUnidadLote(Request $request)
    {
        $request->validate([
            'codigo_unidad' => 'required|string|max:20|unique:unidades_curriculares,codigo_unidad',
            'nombre_unidad' => 'required|string|max:100',
            'archivo_pdf' => 'required|mimes:pdf|max:10240', // 10MB
        ]);

        try {
            DB::beginTransaction();

            $profesorId = Auth::id();

            // 1. Crear Unidad Curricular
            $unidadId = DB::table('unidades_curriculares')->insertGetId([
                'codigo_unidad' => $request->codigo_unidad,
                'nombre_unidad' => $request->nombre_unidad,
                'descripcion' => $request->descripcion,
                'activo' => true
            ]);

            // 2. Asignar Profesor a la Unidad
            DB::table('asignacion_profesores')->insert([
                'id_unidad' => $unidadId,
                'id_profesor' => $profesorId,
                'rol_profesor' => 'titular',
                'fecha_asignacion' => now(),
                'activo' => true
            ]);

            // 3. Crear Chat de la Unidad
            $chatId = DB::table('chats')->insertGetId([
                'nombre_chat' => $request->nombre_unidad . ' - General',
                'descripcion' => 'Chat general de la unidad ' . $request->nombre_unidad,
                'id_unidad' => $unidadId,
                'tipo_chat' => 'unidad_curricular',
                'creado_por' => $profesorId,
                'activo' => true,
                'fecha_creacion' => now()
            ]);

            // Agregar profesor al chat
            DB::table('participantes_chat')->insert([
                'id_chat' => $chatId,
                'id_usuario' => $profesorId,
                'rol_participante' => 'creador',
                'activo' => true,
                'fecha_union' => now()
            ]);

            // 4. Procesar PDF
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($request->file('archivo_pdf')->getPathname());
            $text = $pdf->getText();
            
            if (empty(trim($text))) {
                $pages = $pdf->getPages();
                foreach ($pages as $page) { $text .= $page->getText(); }
            }

            // El PDF tiene bloques separados: primero Nombres/Cédulas, luego Emails.
            $lines = explode("\n", $text);
            $datosEstudiantes = [];
            $correosEncontrados = [];

            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line) || str_contains($line, 'cedula nombre') || str_contains($line, 'Página')) continue;

                // Si es un correo
                if (preg_match('/[a-zA-Z0-9._%+-]+@gmail\.com/', $line, $emailMatch)) {
                    $correosEncontrados[] = $emailMatch[0];
                } 
                // Si es Cédula + Nombre (Ej: 27745764 BRAYAN MARIANI)
                elseif (preg_match('/^(\d{1,2}(?:\.?\d{3}){2,3}|\d{6,10})\s+(.+)$/', $line, $matches)) {
                    $datosEstudiantes[] = [
                        'cedula' => preg_replace('/[^0-9]/', '', $matches[1]),
                        'nombreCompleto' => trim($matches[2])
                    ];
                }
            }

            $estudiantesProcesados = 0;
            $totalEstudiantes = count($datosEstudiantes);
            $totalCorreos = count($correosEncontrados);

            // Unir por índice (asumiendo que el orden es el mismo)
            for ($i = 0; $i < $totalEstudiantes; $i++) {
                $estudiante = $datosEstudiantes[$i];
                $correo = isset($correosEncontrados[$i]) ? $correosEncontrados[$i] : null;

                if (!$correo) continue;

                $partesNombre = explode(' ', $estudiante['nombreCompleto'], 2);
                $nombre = $partesNombre[0];
                $apellido = isset($partesNombre[1]) ? $partesNombre[1] : 'S/A';
                $cedula = $estudiante['cedula'];

                // Buscar o crear usuario
                $usuario = DB::table('usuarios')->where('correo', $correo)->first();
                
                if (!$usuario) {
                    try {
                        $usuarioId = DB::table('usuarios')->insertGetId([
                            'nombre' => $nombre,
                            'apellido' => $apellido,
                            'cedula' => $cedula,
                            'correo' => $correo,
                            'contrasena_hash' => \Illuminate\Support\Facades\Hash::make($cedula),
                            'id_rol' => 3, // Estudiante
                            'activo' => true,
                            'fecha_registro' => now()
                        ]);
                        
                        // Crear registro en tabla estudiantes (id_estudiante es PK y FK a id_usuario)
                        DB::table('estudiantes')->insert([
                            'id_estudiante' => $usuarioId,
                            'codigo_estudiante' => 'EST-' . $cedula,
                            'fecha_ingreso' => now()
                        ]);
                        
                        $usuario = (object)['id_usuario' => $usuarioId];
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error("Error creando estudiante: " . $e->getMessage());
                        continue;
                    }
                } else {
                    // Si el usuario ya existe, asegurar que tenga perfil de estudiante
                    $esEstudiante = DB::table('estudiantes')->where('id_estudiante', $usuario->id_usuario)->exists();
                    if (!$esEstudiante) {
                        DB::table('estudiantes')->insert([
                            'id_estudiante' => $usuario->id_usuario,
                            'codigo_estudiante' => 'EST-' . ($usuario->cedula ?? $usuario->id_usuario),
                            'fecha_ingreso' => now()
                        ]);
                    }
                }

                // Inscribir en la unidad
                $inscrito = DB::table('inscripciones_estudiantes')
                    ->where('id_unidad', $unidadId)
                    ->where('id_estudiante', $usuario->id_usuario)
                    ->exists();

                if (!$inscrito) {
                    DB::table('inscripciones_estudiantes')->insert([
                        'id_estudiante' => $usuario->id_usuario,
                        'id_unidad' => $unidadId,
                        'fecha_inscripcion' => now(),
                        'estado' => 'activo'
                    ]);

                    DB::table('participantes_chat')->insert([
                        'id_chat' => $chatId,
                        'id_usuario' => $usuario->id_usuario,
                        'rol_participante' => 'miembro',
                        'activo' => true,
                        'fecha_union' => now()
                    ]);
                    
                    $estudiantesProcesados++;
                }
            }

            DB::commit();

            return redirect()->route('profesor.unidades')
                ->with('success', "Unidad creada exitosamente. Se procesaron $estudiantesProcesados estudiantes.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }

    public function destroyUnidad($id)
    {
        $profesorId = Auth::id();

        // Verificar que la unidad pertenezca al profesor
        $existe = DB::table('asignacion_profesores')
            ->where('id_unidad', $id)
            ->where('id_profesor', $profesorId)
            ->exists();

        if (!$existe) {
            return back()->with('error', 'No tienes permisos para eliminar esta unidad.');
        }

        try {
            DB::beginTransaction();

            // 1. Obtener el chat asociado a la unidad
            $chat = DB::table('chats')
                ->where('id_unidad', $id)
                ->where('tipo_chat', 'unidad_curricular')
                ->first();

            if ($chat) {
                // 2. Eliminar mensajes y adjuntos del chat
                $mensajesIds = DB::table('mensajes')->where('id_chat', $chat->id_chat)->pluck('id_mensaje');
                DB::table('documentos_adjuntos')->whereIn('id_mensaje', $mensajesIds)->delete();
                DB::table('mensajes')->where('id_chat', $chat->id_chat)->delete();
                
                // 3. Eliminar participantes del chat
                DB::table('participantes_chat')->where('id_chat', $chat->id_chat)->delete();
                
                // 4. Eliminar el chat
                DB::table('chats')->where('id_chat', $chat->id_chat)->delete();
            }

            // 5. Eliminar inscripciones de estudiantes
            DB::table('inscripciones_estudiantes')->where('id_unidad', $id)->delete();

            // 6. Eliminar asignaciones de profesores
            DB::table('asignacion_profesores')->where('id_unidad', $id)->delete();

            // 7. Eliminar la unidad curricular
            DB::table('unidades_curriculares')->where('id_unidad', $id)->delete();

            DB::commit();

            return redirect()->route('profesor.unidades')
                ->with('success', 'Unidad y chat eliminados correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }

    /**
     * Calificar un trabajo de estudiante
     */
    public function calificarTrabajo($id)
    {
        $profesorId = Auth::id();

        // Verificar que el trabajo pertenezca a una unidad del profesor
        $entrega = DB::table('entregas_trabajos as et')
            ->join('unidades_curriculares as uc', 'et.id_unidad', '=', 'uc.id_unidad')
            ->join('asignacion_profesores as ap', 'uc.id_unidad', '=', 'ap.id_unidad')
            ->join('estudiantes as est', 'et.id_estudiante', '=', 'est.id_estudiante')
            ->join('usuarios as u', 'est.id_estudiante', '=', 'u.id_usuario')
            ->where('et.id_entrega', $id)
            ->where('ap.id_profesor', $profesorId)
            ->where('ap.activo', true)
            ->select('et.*', 'uc.nombre_unidad', 'u.nombre as nombre_estudiante', 'u.apellido as apellido_estudiante')
            ->first();

        if (!$entrega) {
            return back()->with('error', 'No tienes permisos para calificar este trabajo.');
        }

        return view('profesor.calificar-trabajo', compact('entrega'));
    }

    /**
     * Guardar calificación de un trabajo
     */
    public function calificarTrabajoStore(Request $request, $id)
    {
        $request->validate([
            'calificacion' => 'required|numeric|min:0|max:100',
            'comentarios_profesor' => 'nullable|string|max:1000'
        ]);

        $profesorId = Auth::id();

        // Verificar permisos
        $existe = DB::table('entregas_trabajos as et')
            ->join('unidades_curriculares as uc', 'et.id_unidad', '=', 'uc.id_unidad')
            ->join('asignacion_profesores as ap', 'uc.id_unidad', '=', 'ap.id_unidad')
            ->where('et.id_entrega', $id)
            ->where('ap.id_profesor', $profesorId)
            ->exists();

        if (!$existe) {
            return back()->with('error', 'No tienes permisos para calificar este trabajo.');
        }

        // Actualizar calificación
        DB::table('entregas_trabajos')
            ->where('id_entrega', $id)
            ->update([
                'calificacion' => $request->calificacion,
                'comentarios_profesor' => $request->comentarios_profesor,
                'estado' => 'revisado',
                'fecha_revision' => now()
            ]);

        // Registrar en bitácora
        BitacoraSistema::registrar(
            'Calificación de Trabajo',
            'Profesor/Académico',
            "El profesor calificó un trabajo con {$request->calificacion}/100"
        );

        return redirect()->route('profesor.trabajos')
            ->with('success', 'Trabajo calificado exitosamente.');
    }

    /**
     * Descargar archivo de trabajo de estudiante
     */
    public function descargarTrabajo($id)
    {
        $profesorId = Auth::id();

        // Verificar permisos y obtener el trabajo
        $entrega = DB::table('entregas_trabajos as et')
            ->join('unidades_curriculares as uc', 'et.id_unidad', '=', 'uc.id_unidad')
            ->join('asignacion_profesores as ap', 'uc.id_unidad', '=', 'ap.id_unidad')
            ->where('et.id_entrega', $id)
            ->where('ap.id_profesor', $profesorId)
            ->where('ap.activo', true)
            ->select('et.*')
            ->first();

        if (!$entrega) {
            abort(403, 'No tienes permisos para descargar este archivo.');
        }

        $filePath = storage_path('app/' . $entrega->ruta_archivo);

        if (!file_exists($filePath)) {
            return back()->with('error', 'El archivo no existe.');
        }

        return response()->download($filePath, $entrega->nombre_archivo);
    }
}