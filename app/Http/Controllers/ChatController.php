<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\BitacoraSistema;

class ChatController extends Controller
{
    /**
     * Mostrar lista de chats del usuario
     */
   /**
 * Mostrar lista de chats del usuario
 */
public function index()
{
    $usuario = Auth::user();
    $userId = $usuario->id_usuario;
    
    // Obtener chats donde el usuario es participante
    $chats = DB::table('chats as c')
        ->select('c.*', 
            DB::raw('(SELECT contenido_texto FROM mensajes WHERE id_chat = c.id_chat ORDER BY fecha_envio DESC LIMIT 1) as ultimo_mensaje'),
            DB::raw('(SELECT fecha_envio FROM mensajes WHERE id_chat = c.id_chat ORDER BY fecha_envio DESC LIMIT 1) as ultima_fecha'),
            DB::raw('(SELECT id_usuario FROM mensajes WHERE id_chat = c.id_chat ORDER BY fecha_envio DESC LIMIT 1) as ultimo_usuario_id')
        )
        ->whereExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))
                  ->from('participantes_chat as pc')
                  ->whereColumn('pc.id_chat', 'c.id_chat')
                  ->where('pc.id_usuario', $userId)
                  ->where('pc.activo', true);
        })
        ->where('c.activo', true)
        ->orderBy('ultima_fecha', 'desc')
        ->get();
    
    // Obtener información del último usuario que envió mensaje
    foreach ($chats as $chat) {
        if ($chat->ultimo_usuario_id) {
            $ultimoUsuario = DB::table('usuarios')
                ->where('id_usuario', $chat->ultimo_usuario_id)
                ->first();
            $chat->ultimo_usuario_nombre = $ultimoUsuario ? $ultimoUsuario->nombre : 'Usuario';
        } else {
            $chat->ultimo_usuario_nombre = 'Nadie';
            $chat->ultimo_mensaje = 'Sin mensajes';
        }
    }
    
    return view('chat.index', compact('chats'));
}

    /**
     * Mostrar un chat específico
     */
  /**
 * Mostrar un chat específico
 */
public function show($id)
{
    $userId = Auth::id();
    
    // Verificar si el usuario tiene acceso al chat
    $acceso = DB::table('participantes_chat')
        ->where('id_chat', $id)
        ->where('id_usuario', $userId)
        ->where('activo', true)
        ->exists();
        
    if (!$acceso) {
        abort(403, 'No tienes acceso a este chat');
    }
    
    // Obtener información del chat
    $chat = DB::table('chats')->where('id_chat', $id)->first();
    
    if (!$chat) {
        abort(404, 'Chat no encontrado');
    }
    
    // Obtener mensajes del chat
    $mensajes = DB::table('mensajes as m')
        ->leftJoin('usuarios as u', 'm.id_usuario', '=', 'u.id_usuario')
        ->leftJoin('roles as r', 'u.id_rol', '=', 'r.id_rol')
        ->leftJoin('documentos_adjuntos as d', 'm.id_mensaje', '=', 'd.id_mensaje')
        ->where('m.id_chat', $id)
        ->where('m.eliminado', false)
        ->select('m.*', 'u.nombre as usuario_nombre', 'u.apellido as usuario_apellido',
                'r.nombre_rol as usuario_rol',
                'd.nombre_archivo', 'd.tipo_archivo', 'd.tamano_archivo', 'd.ruta_almacenamiento')
        ->orderBy('m.fecha_envio', 'asc')
        ->get();
    
    // Obtener participantes del chat
    $participantes = DB::table('participantes_chat as pc')
        ->join('usuarios as u', 'pc.id_usuario', '=', 'u.id_usuario')
        ->where('pc.id_chat', $id)
        ->where('pc.activo', true)
        ->select('u.id_usuario', 'u.nombre', 'u.apellido', 'u.correo', 'pc.rol_participante')
        ->get();
    
    // Obtener lista de todos los chats para el sidebar (CON los últimos mensajes)
    $chats = DB::table('chats as c')
        ->select('c.*', 
            DB::raw('(SELECT contenido_texto FROM mensajes WHERE id_chat = c.id_chat ORDER BY fecha_envio DESC LIMIT 1) as ultimo_mensaje'),
            DB::raw('(SELECT fecha_envio FROM mensajes WHERE id_chat = c.id_chat ORDER BY fecha_envio DESC LIMIT 1) as ultima_fecha'),
            DB::raw('(SELECT id_usuario FROM mensajes WHERE id_chat = c.id_chat ORDER BY fecha_envio DESC LIMIT 1) as ultimo_usuario_id')
        )
        ->whereExists(function ($query) use ($userId) {
            $query->select(DB::raw(1))
                  ->from('participantes_chat as pc')
                  ->whereColumn('pc.id_chat', 'c.id_chat')
                  ->where('pc.id_usuario', $userId)
                  ->where('pc.activo', true);
        })
        ->where('c.activo', true)
        ->orderBy('ultima_fecha', 'desc')
        ->get();
    
    // Obtener información del último usuario que envió mensaje y agregar propiedades adicionales
    foreach ($chats as $chatItem) {
        if ($chatItem->ultimo_usuario_id) {
            $ultimoUsuario = DB::table('usuarios')
                ->where('id_usuario', $chatItem->ultimo_usuario_id)
                ->first();
            $chatItem->ultimo_usuario_nombre = $ultimoUsuario ? $ultimoUsuario->nombre : 'Usuario';
        } else {
            $chatItem->ultimo_usuario_nombre = 'Nadie';
            $chatItem->ultimo_mensaje = 'Sin mensajes';
        }
        
        // Agregar conteo de participantes
        $chatItem->participants_count = DB::table('participantes_chat')
            ->where('id_chat', $chatItem->id_chat)
            ->where('activo', true)
            ->count();
        
        // Verificar si hay mensajes no leídos (simplificado - siempre false por ahora)
        // TODO: Implementar sistema de mensajes leídos/no leídos con tabla de lecturas
        $chatItem->has_unread = false;
    }
    
    return view('chat.show', compact('chat', 'mensajes', 'participantes', 'chats'));
}

    /**
     * Mostrar formulario para crear chat
     */
    public function create()
    {
        $usuario = Auth::user();
        
        // Obtener unidades curriculares según el rol
        if ($usuario->esEstudiante()) {
            $unidades = DB::table('inscripciones_estudiantes as ie')
                ->join('unidades_curriculares as uc', 'ie.id_unidad', '=', 'uc.id_unidad')
                ->where('ie.id_estudiante', $usuario->id_usuario)
                ->where('ie.estado', 'activo')
                ->select('uc.*')
                ->get();
        } elseif ($usuario->esProfesor()) {
            $unidades = DB::table('asignacion_profesores as ap')
                ->join('unidades_curriculares as uc', 'ap.id_unidad', '=', 'uc.id_unidad')
                ->where('ap.id_profesor', $usuario->id_usuario)
                ->select('uc.*')
                ->get();
        } else {
            $unidades = collect();
        }
        
        return view('chat.create', compact('unidades'));
    }

    /**
     * Guardar nuevo chat
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre_chat' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
            'id_unidad' => 'nullable|exists:unidades_curriculares,id_unidad',
            'tipo_chat' => 'required|in:privado,grupal,unidad_curricular'
        ]);
        
        // Crear chat
        $chatId = DB::table('chats')->insertGetId([
            'nombre_chat' => $request->nombre_chat,
            'descripcion' => $request->descripcion,
            'id_unidad' => $request->id_unidad,
            'tipo_chat' => $request->tipo_chat,
            'creado_por' => Auth::id(),
            'activo' => true,
            'fecha_creacion' => now()
        ]);
        
        // Agregar creador como participante
        DB::table('participantes_chat')->insert([
            'id_chat' => $chatId,
            'id_usuario' => Auth::id(),
            'rol_participante' => 'creador',
            'activo' => true,
            'fecha_union' => now()
        ]);
        
        // Si es chat de unidad, agregar participantes automáticamente
        if ($request->tipo_chat == 'unidad_curricular' && $request->id_unidad) {
            $this->agregarParticipantesUnidad($chatId, $request->id_unidad);
        }
        
        return redirect()->route('chat.show', $chatId)
            ->with('success', 'Chat creado exitosamente');
    }

    /**
     * Enviar mensaje en un chat
     */
    public function enviarMensaje(Request $request, $idChat)
    {
        $request->validate([
            'contenido' => 'required_without:archivo|string',
            'archivo' => 'nullable|file|max:10240',
        ]);
        
        // Verificar acceso al chat
        $acceso = DB::table('participantes_chat')
            ->where('id_chat', $idChat)
            ->where('id_usuario', Auth::id())
            ->where('activo', true)
            ->exists();
            
        if (!$acceso) {
            abort(403, 'No tienes acceso a este chat');
        }
        
        // Insertar mensaje
        $mensajeId = DB::table('mensajes')->insertGetId([
            'id_chat' => $idChat,
            'id_usuario' => Auth::id(),
            'contenido_texto' => $request->contenido,
            'tipo_mensaje' => $request->hasFile('archivo') ? 'documento' : 'texto',
            'fecha_envio' => now(),
            'eliminado' => false
        ]);
        
        // Manejar archivo adjunto
        if ($request->hasFile('archivo')) {
            $archivo = $request->file('archivo');
            $nombreArchivo = time() . '_' . $archivo->getClientOriginalName();
            $ruta = 'documentos_chat/' . $nombreArchivo;
            
            // Guardar archivo (en storage/app/public)
            $archivo->storeAs('public/documentos_chat', $nombreArchivo);
            
            DB::table('documentos_adjuntos')->insert([
                'id_mensaje' => $mensajeId,
                'nombre_archivo' => $archivo->getClientOriginalName(),
                'tipo_archivo' => $archivo->getMimeType(),
                'tamano_archivo' => $archivo->getSize(),
                'ruta_almacenamiento' => $ruta,
                'fecha_subida' => now()
            ]);
        }
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Mensaje enviado',
                'id_mensaje' => $mensajeId,
                'html' => view('chat.partials.message', [
                    'mensaje' => DB::table('mensajes as m')
                        ->leftJoin('usuarios as u', 'm.id_usuario', '=', 'u.id_usuario')
                        ->leftJoin('roles as r', 'u.id_rol', '=', 'r.id_rol')
                        ->leftJoin('documentos_adjuntos as d', 'm.id_mensaje', '=', 'd.id_mensaje')
                        ->where('m.id_mensaje', $mensajeId)
                        ->select('m.*', 'u.nombre as usuario_nombre', 'u.apellido as usuario_apellido',
                                'r.nombre_rol as usuario_rol',
                                'd.nombre_archivo', 'd.tipo_archivo', 'd.tamano_archivo', 'd.ruta_almacenamiento')
                        ->first(),
                    'showAvatar' => true
                ])->render()
            ]);
        }

        return back()->with('success', 'Mensaje enviado');
    }

    /**
     * Obtener nuevos mensajes para el chat (AJAX)
     */
    public function getNuevosMensajes(Request $request, $idChat)
    {
        $lastMessageId = $request->query('last_id');
        
        $mensajes = DB::table('mensajes as m')
            ->leftJoin('usuarios as u', 'm.id_usuario', '=', 'u.id_usuario')
            ->leftJoin('roles as r', 'u.id_rol', '=', 'r.id_rol')
            ->leftJoin('documentos_adjuntos as d', 'm.id_mensaje', '=', 'd.id_mensaje')
            ->where('m.id_chat', $idChat)
            ->where('m.id_mensaje', '>', $lastMessageId)
            ->where('m.eliminado', false)
            ->where('m.id_usuario', '!=', Auth::id()) // Solo mensajes de otros
            ->select('m.*', 'u.nombre as usuario_nombre', 'u.apellido as usuario_apellido',
                    'r.nombre_rol as usuario_rol',
                    'd.nombre_archivo', 'd.tipo_archivo', 'd.tamano_archivo', 'd.ruta_almacenamiento')
            ->orderBy('m.fecha_envio', 'asc')
            ->get();
            
        $html = '';
        foreach ($mensajes as $mensaje) {
            $html .= view('chat.partials.message', [
                'mensaje' => $mensaje,
                'showAvatar' => true // Simplificado para polling
            ])->render();
        }
        
        return response()->json([
            'html' => $html,
            'count' => $mensajes->count(),
            'last_id' => $mensajes->count() > 0 ? $mensajes->last()->id_mensaje : $lastMessageId
        ]);
    }

    /**
     * Publicar una tarea en el chat (Solo Profesores)
     */
    public function publicarTarea(Request $request, $idChat)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string',
            'fecha_limite' => 'nullable|date|after:now',
        ]);

        // Verificar que el usuario sea profesor y tenga acceso al chat
        $usuario = Auth::user();
        if (!$usuario->esProfesor()) {
            abort(403, 'Solo los profesores pueden publicar tareas');
        }

        $acceso = DB::table('participantes_chat')
            ->where('id_chat', $idChat)
            ->where('id_usuario', $usuario->id_usuario)
            ->where('activo', true)
            ->exists();

        if (!$acceso) {
            abort(403, 'No tienes acceso a este chat');
        }

        DB::beginTransaction();
        try {
            // 1. Crear la tarea
            $tareaId = DB::table('tareas')->insertGetId([
                'id_chat' => $idChat,
                'id_profesor' => $usuario->id_usuario,
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'fecha_limite' => $request->fecha_limite,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // 2. Insertar mensaje tipo 'tarea'
            DB::table('mensajes')->insert([
                'id_chat' => $idChat,
                'id_usuario' => $usuario->id_usuario,
                'contenido_texto' => json_encode([
                    'id_tarea' => $tareaId,
                    'titulo' => $request->titulo,
                    'descripcion' => Str::limit($request->descripcion, 100)
                ]),
                'tipo_mensaje' => 'tarea',
                'fecha_envio' => now(),
                'eliminado' => false
            ]);

            // Registrar en bitácora
            BitacoraSistema::registrar(
                'Publicación de Tarea',
                'Chat/Académico',
                "El profesor publicó la tarea: {$request->titulo}"
            );

            DB::commit();
            return back()->with('success', 'Tarea publicada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al publicar la tarea: ' . $e->getMessage());
        }
    }

    /**
     * Agregar participantes de una unidad curricular
     */
    private function agregarParticipantesUnidad($idChat, $idUnidad)
    {
        // Obtener estudiantes inscritos
        $estudiantes = DB::table('inscripciones_estudiantes')
            ->where('id_unidad', $idUnidad)
            ->where('estado', 'activo')
            ->pluck('id_estudiante');
        
        // Obtener profesores asignados
        $profesores = DB::table('asignacion_profesores')
            ->where('id_unidad', $idUnidad)
            ->pluck('id_profesor');
        
        // Combinar y agregar participantes
        $participantes = $estudiantes->merge($profesores)->unique();
        
        $datosParticipantes = [];
        foreach ($participantes as $idUsuario) {
            $datosParticipantes[] = [
                'id_chat' => $idChat,
                'id_usuario' => $idUsuario,
                'rol_participante' => 'miembro',
                'activo' => true,
                'fecha_union' => now()
            ];
        }
        
        if (!empty($datosParticipantes)) {
            DB::table('participantes_chat')->insert($datosParticipantes);
        }
    }

    /**
     * Agregar participante manualmente
     */
    public function agregarParticipante(Request $request, $idChat)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario'
        ]);
        
        // Verificar si ya es participante
        $existe = DB::table('participantes_chat')
            ->where('id_chat', $idChat)
            ->where('id_usuario', $request->id_usuario)
            ->exists();
            
        if ($existe) {
            return back()->with('error', 'El usuario ya es participante del chat');
        }
        
        DB::table('participantes_chat')->insert([
            'id_chat' => $idChat,
            'id_usuario' => $request->id_usuario,
            'rol_participante' => 'miembro',
            'activo' => true,
            'fecha_union' => now()
        ]);
        
        return back()->with('success', 'Participante agregado exitosamente');
    }
}