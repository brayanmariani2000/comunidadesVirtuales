<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Sembrar la base de datos de la aplicación.
     */
    public function run(): void
    {
        // Limpiar tablas en orden inverso a la creación
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('bitacora_sistema')->truncate();
        DB::table('sesiones_usuario')->truncate();
        DB::table('entregas_trabajos')->truncate();
        DB::table('documentos_adjuntos')->truncate();
        DB::table('mensajes')->truncate();
        DB::table('participantes_chat')->truncate();
        DB::table('chats')->truncate();
        DB::table('asignacion_profesores')->truncate();
        DB::table('inscripciones_estudiantes')->truncate();
        DB::table('unidades_curriculares')->truncate();
        DB::table('profesores')->truncate();
        DB::table('estudiantes')->truncate();
        DB::table('usuarios')->truncate();
        DB::table('roles')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ==================== ROLES ====================
        $roles = [
            ['id_rol' => 1, 'nombre_rol' => 'Administrador', 'descripcion' => 'Acceso completo al sistema', 'activo' => true, 'fecha_creacion' => now()],
            ['id_rol' => 2, 'nombre_rol' => 'Profesor', 'descripcion' => 'Gestiona unidades curriculares y estudiantes', 'activo' => true, 'fecha_creacion' => now()],
            ['id_rol' => 3, 'nombre_rol' => 'Estudiante', 'descripcion' => 'Accede a unidades y materiales', 'activo' => true, 'fecha_creacion' => now()],
        ];
        DB::table('roles')->insert($roles);

        // ==================== USUARIOS ====================
        $usuarios = [
            // Administrador
            [
                'id_usuario' => 1,
                'id_rol' => 1,
                'nombre' => 'Admin',
                'apellido' => 'Sistema',
                'cedula' => '12345678',
                'telefono' => '04121234567',
                'correo' => 'admin@comunidad.com',
                'contrasena_hash' => Hash::make('admin123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => now()
            ],
            // Profesores
            [
                'id_usuario' => 2,
                'id_rol' => 2,
                'nombre' => 'Carlos',
                'apellido' => 'Gómez',
                'cedula' => '11111111',
                'telefono' => '04141111111',
                'correo' => 'carlos.gomez@comunidad.com',
                'contrasena_hash' => Hash::make('profesor123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 3,
                'id_rol' => 2,
                'nombre' => 'Ana',
                'apellido' => 'López',
                'cedula' => '22222222',
                'telefono' => '04142222222',
                'correo' => 'ana.lopez@comunidad.com',
                'contrasena_hash' => Hash::make('profesor123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 4,
                'id_rol' => 2,
                'nombre' => 'Luis',
                'apellido' => 'Ramírez',
                'cedula' => '33333333',
                'telefono' => '04143333333',
                'correo' => 'luis.ramirez@comunidad.com',
                'contrasena_hash' => Hash::make('profesor123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 5,
                'id_rol' => 2,
                'nombre' => 'María',
                'apellido' => 'Fernández',
                'cedula' => '44444444',
                'telefono' => '04144444444',
                'correo' => 'maria.fernandez@comunidad.com',
                'contrasena_hash' => Hash::make('profesor123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 6,
                'id_rol' => 2,
                'nombre' => 'Pedro',
                'apellido' => 'Martínez',
                'cedula' => '55555555',
                'telefono' => '04145555555',
                'correo' => 'pedro.martinez@comunidad.com',
                'contrasena_hash' => Hash::make('profesor123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            // Estudiantes (10 estudiantes)
            [
                'id_usuario' => 7,
                'id_rol' => 3,
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'cedula' => '20111111',
                'telefono' => '04261111111',
                'correo' => 'juan.perez@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 8,
                'id_rol' => 3,
                'nombre' => 'María',
                'apellido' => 'González',
                'cedula' => '20222222',
                'telefono' => '04262222222',
                'correo' => 'maria.gonzalez@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 9,
                'id_rol' => 3,
                'nombre' => 'José',
                'apellido' => 'Rodríguez',
                'cedula' => '20333333',
                'telefono' => '04263333333',
                'correo' => 'jose.rodriguez@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 10,
                'id_rol' => 3,
                'nombre' => 'Carmen',
                'apellido' => 'Sánchez',
                'cedula' => '20444444',
                'telefono' => '04264444444',
                'correo' => 'carmen.sanchez@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 11,
                'id_rol' => 3,
                'nombre' => 'Miguel',
                'apellido' => 'Torres',
                'cedula' => '20555555',
                'telefono' => '04265555555',
                'correo' => 'miguel.torres@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 12,
                'id_rol' => 3,
                'nombre' => 'Laura',
                'apellido' => 'Díaz',
                'cedula' => '20666666',
                'telefono' => '04266666666',
                'correo' => 'laura.diaz@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 13,
                'id_rol' => 3,
                'nombre' => 'Diego',
                'apellido' => 'Morales',
                'cedula' => '20777777',
                'telefono' => '04267777777',
                'correo' => 'diego.morales@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 14,
                'id_rol' => 3,
                'nombre' => 'Andrea',
                'apellido' => 'Vega',
                'cedula' => '20888888',
                'telefono' => '04268888888',
                'correo' => 'andrea.vega@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 15,
                'id_rol' => 3,
                'nombre' => 'Roberto',
                'apellido' => 'Castro',
                'cedula' => '20999999',
                'telefono' => '04269999999',
                'correo' => 'roberto.castro@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
            [
                'id_usuario' => 16,
                'id_rol' => 3,
                'nombre' => 'Sofia',
                'apellido' => 'Herrera',
                'cedula' => '21000000',
                'telefono' => '04260000000',
                'correo' => 'sofia.herrera@estudiante.com',
                'contrasena_hash' => Hash::make('estudiante123'),
                'activo' => true,
                'fecha_registro' => now(),
                'ultimo_acceso' => null
            ],
        ];
        DB::table('usuarios')->insert($usuarios);

        // ==================== PROFESORES ====================
        $profesores = [
            ['id_profesor' => 2, 'codigo_profesor' => 'PROF001', 'especialidad' => 'Ingeniería de Software', 'grado_academico' => 'Magister', 'fecha_contratacion' => '2020-01-15', 'fecha_actualizacion' => now()],
            ['id_profesor' => 3, 'codigo_profesor' => 'PROF002', 'especialidad' => 'Matemáticas', 'grado_academico' => 'Doctora', 'fecha_contratacion' => '2019-08-20', 'fecha_actualizacion' => now()],
            ['id_profesor' => 4, 'codigo_profesor' => 'PROF003', 'especialidad' => 'Base de Datos', 'grado_academico' => 'Magister', 'fecha_contratacion' => '2021-02-10', 'fecha_actualizacion' => now()],
            ['id_profesor' => 5, 'codigo_profesor' => 'PROF004', 'especialidad' => 'Redes y Comunicaciones', 'grado_academico' => 'Ingeniera', 'fecha_contratacion' => '2020-09-01', 'fecha_actualizacion' => now()],
            ['id_profesor' => 6, 'codigo_profesor' => 'PROF005', 'especialidad' => 'Sistemas Operativos', 'grado_academico' => 'Magister', 'fecha_contratacion' => '2018-03-15', 'fecha_actualizacion' => now()],
        ];
        DB::table('profesores')->insert($profesores);

        // ==================== ESTUDIANTES ====================
        $estudiantes = [
            ['id_estudiante' => 7, 'codigo_estudiante' => 'EST001', 'fecha_ingreso' => '2023-01-15', 'carrera' => 'Ingeniería Informática', 'semestre_actual' => 3, 'fecha_actualizacion' => now()],
            ['id_estudiante' => 8, 'codigo_estudiante' => 'EST002', 'fecha_ingreso' => '2023-01-15', 'carrera' => 'Ingeniería Informática', 'semestre_actual' => 3, 'fecha_actualizacion' => now()],
            ['id_estudiante' => 9, 'codigo_estudiante' => 'EST003', 'fecha_ingreso' => '2023-01-15', 'carrera' => 'Ingeniería de Sistemas', 'semestre_actual' => 3, 'fecha_actualizacion' => now()],
            ['id_estudiante' => 10, 'codigo_estudiante' => 'EST004', 'fecha_ingreso' => '2022-09-01', 'carrera' => 'Ingeniería Informática', 'semestre_actual' => 5, 'fecha_actualizacion' => now()],
            ['id_estudiante' => 11, 'codigo_estudiante' => 'EST005', 'fecha_ingreso' => '2022-09-01', 'carrera' => 'Ingeniería de Sistemas', 'semestre_actual' => 5, 'fecha_actualizacion' => now()],
            ['id_estudiante' => 12, 'codigo_estudiante' => 'EST006', 'fecha_ingreso' => '2023-01-15', 'carrera' => 'Ingeniería Informática', 'semestre_actual' => 3, 'fecha_actualizacion' => now()],
            ['id_estudiante' => 13, 'codigo_estudiante' => 'EST007', 'fecha_ingreso' => '2022-09-01', 'carrera' => 'Ingeniería de Sistemas', 'semestre_actual' => 5, 'fecha_actualizacion' => now()],
            ['id_estudiante' => 14, 'codigo_estudiante' => 'EST008', 'fecha_ingreso' => '2023-01-15', 'carrera' => 'Ingeniería Informática', 'semestre_actual' => 3, 'fecha_actualizacion' => now()],
            ['id_estudiante' => 15, 'codigo_estudiante' => 'EST009', 'fecha_ingreso' => '2022-09-01', 'carrera' => 'Ingeniería de Sistemas', 'semestre_actual' => 5, 'fecha_actualizacion' => now()],
            ['id_estudiante' => 16, 'codigo_estudiante' => 'EST010', 'fecha_ingreso' => '2023-01-15', 'carrera' => 'Ingeniería Informática', 'semestre_actual' => 3, 'fecha_actualizacion' => now()],
        ];
        DB::table('estudiantes')->insert($estudiantes);

        // ==================== UNIDADES CURR ICULARES ====================
        $unidades = [
            ['id_unidad' => 1, 'codigo_unidad' => 'INF-101', 'nombre_unidad' => 'Programación I', 'descripcion' => 'Fundamentos de programación estructurada', 'creditos' => 4, 'horas_semanales' => 6, 'periodo_academico' => '2025-1', 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
            ['id_unidad' => 2, 'codigo_unidad' => 'MAT-201', 'nombre_unidad' => 'Matemáticas Discretas', 'descripcion' => 'Lógica, conjuntos y teoría de grafos', 'creditos' => 3, 'horas_semanales' => 4, 'periodo_academico' => '2025-1', 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
            ['id_unidad' => 3, 'codigo_unidad' => 'BD-301', 'nombre_unidad' => 'Base de Datos', 'descripcion' => 'Diseño y gestión de bases de datos relacionales', 'creditos' => 4, 'horas_semanales' => 6, 'periodo_academico' => '2025-1', 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
            ['id_unidad' => 4, 'codigo_unidad' => 'RED-401', 'nombre_unidad' => 'Redes de Computadoras', 'descripcion' => 'Arquitectura y protocolos de redes', 'creditos' => 4, 'horas_semanales' => 6, 'periodo_academico' => '2025-1', 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
            ['id_unidad' => 5, 'codigo_unidad' => 'SO-501', 'nombre_unidad' => 'Sistemas Operativos', 'descripcion' => 'Gestión de procesos y memoria', 'creditos' => 4, 'horas_semanales' => 6, 'periodo_academico' => '2025-1', 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
            ['id_unidad' => 6, 'codigo_unidad' => 'WEB-201', 'nombre_unidad' => 'Desarrollo Web', 'descripcion' => 'HTML, CSS, JavaScript y frameworks modernos', 'creditos' => 3, 'horas_semanales' => 5, 'periodo_academico' => '2025-1', 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
        ];
        DB::table('unidades_curriculares')->insert($unidades);

        // ==================== ASIGNACIONES PROFESORES ====================
        $asignaciones = [
            ['id_profesor' => 2, 'id_unidad' => 1, 'rol_profesor' => 'titular', 'fecha_asignacion' => now(), 'activo' => true],
            ['id_profesor' => 3, 'id_unidad' => 2, 'rol_profesor' => 'titular', 'fecha_asignacion' => now(), 'activo' => true],
            ['id_profesor' => 4, 'id_unidad' => 3, 'rol_profesor' => 'titular', 'fecha_asignacion' => now(), 'activo' => true],
            ['id_profesor' => 5, 'id_unidad' => 4, 'rol_profesor' => 'titular', 'fecha_asignacion' => now(), 'activo' => true],
            ['id_profesor' => 6, 'id_unidad' => 5, 'rol_profesor' => 'titular', 'fecha_asignacion' => now(), 'activo' => true],
            ['id_profesor' => 2, 'id_unidad' => 6, 'rol_profesor' => 'titular', 'fecha_asignacion' => now(), 'activo' => true],
        ];
        DB::table('asignacion_profesores')->insert($asignaciones);

        // ==================== INSCRIPCIONES ESTUDIANTES ====================
        $inscripciones = [];
        // Inscribir estudiantes 7-10 en las primeras 3 unidades
        for ($est = 7; $est <= 10; $est++) {
            for ($unidad = 1; $unidad <= 3; $unidad++) {
                $inscripciones[] = [
                    'id_estudiante' => $est,
                    'id_unidad' => $unidad,
                    'estado' => 'activo',
                    'calificacion_final' => null,
                    'fecha_inscripcion' => now(),
                    'fecha_actualizacion' => now()
                ];
            }
        }
        // Inscribir estudiantes 11-16 en unidades 2, 3 y 4
        for ($est = 11; $est <= 16; $est++) {
            for ($unidad = 2; $unidad <= 4; $unidad++) {
                $inscripciones[] = [
                    'id_estudiante' => $est,
                    'id_unidad' => $unidad,
                    'estado' => 'activo',
                    'calificacion_final' => null,
                    'fecha_inscripcion' => now(),
                    'fecha_actualizacion' => now()
                ];
            }
        }
        DB::table('inscripciones_estudiantes')->insert($inscripciones);

        // ==================== CHATS ====================
        $chats = [
            ['id_chat' => 1, 'nombre_chat' => 'Programación I - General', 'descripcion' => 'Chat general de la materia', 'id_unidad' => 1, 'tipo_chat' => 'unidad_curricular', 'creado_por' => 2, 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
            ['id_chat' => 2, 'nombre_chat' => 'Matemáticas Discretas - Dudas', 'descripcion' => 'Espacio para resolver dudas', 'id_unidad' => 2, 'tipo_chat' => 'unidad_curricular', 'creado_por' => 3, 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
            ['id_chat' => 3, 'nombre_chat' => 'Base de Datos - Proyecto', 'descripcion' => 'Coordinación del proyecto final', 'id_unidad' => 3, 'tipo_chat' => 'unidad_curricular', 'creado_por' => 4, 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
            ['id_chat' => 4, 'nombre_chat' => 'Grupo de Estudio', 'descripcion' => 'Estudiantes del turno mañana', 'id_unidad' => null, 'tipo_chat' => 'grupal', 'creado_por' => 7, 'activo' => true, 'fecha_creacion' => now(), 'fecha_actualizacion' => now()],
        ];
        DB::table('chats')->insert($chats);

        // ==================== PARTICIPANTES CHAT ====================
        $participantes = [
            // Chat 1: Programación I
            ['id_chat' => 1, 'id_usuario' => 2, 'rol_participante' => 'creador', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 1, 'id_usuario' => 7, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 1, 'id_usuario' => 8, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 1, 'id_usuario' => 9, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 1, 'id_usuario' => 10, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            // Chat 2: Matemáticas Discretas
            ['id_chat' => 2, 'id_usuario' => 3, 'rol_participante' => 'creador', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 2, 'id_usuario' => 7, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 2, 'id_usuario' => 8, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 2, 'id_usuario' => 11, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 2, 'id_usuario' => 12, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            // Chat 3: Base de Datos
            ['id_chat' => 3, 'id_usuario' => 4, 'rol_participante' => 'creador', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 3, 'id_usuario' => 7, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 3, 'id_usuario' => 8, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 3, 'id_usuario' => 11, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            // Chat 4: Grupo de Estudio
            ['id_chat' => 4, 'id_usuario' => 7, 'rol_participante' => 'creador', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 4, 'id_usuario' => 8, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
            ['id_chat' => 4, 'id_usuario' => 9, 'rol_participante' => 'miembro', 'activo' => true, 'fecha_union' => now()],
        ];
        DB::table('participantes_chat')->insert($participantes);

        // ==================== MENSAJES ====================
        $mensajes = [
            // Chat 1
            ['id_chat' => 1, 'id_usuario' => 2, 'contenido_texto' => '¡Bienvenidos al chat de Programación I! Aquí podrán hacer sus consultas.', 'tipo_mensaje' => 'texto', 'eliminado' => false, 'fecha_envio' => now()->subHours(2)],
            ['id_chat' => 1, 'id_usuario' => 7, 'contenido_texto' => 'Gracias profesor! Tengo una duda sobre los bucles while.', 'tipo_mensaje' => 'texto', 'eliminado' => false, 'fecha_envio' => now()->subHours(1)],
            ['id_chat' => 1, 'id_usuario' => 2, 'contenido_texto' => 'Con gusto te ayudo. ¿Cuál es tu duda específica?', 'tipo_mensaje' => 'texto', 'eliminado' => false, 'fecha_envio' => now()->subMinutes(30)],
            // Chat 2
            ['id_chat' => 2, 'id_usuario' => 3, 'contenido_texto' => 'Buenos días! Recuerden revisar el material sobre conjuntos que subí.', 'tipo_mensaje' => 'texto', 'eliminado' => false, 'fecha_envio' => now()->subHours(3)],
            ['id_chat' => 2, 'id_usuario' => 11, 'contenido_texto' => 'Profesora, ¿para cuándo es la próxima evaluación?', 'tipo_mensaje' => 'texto', 'eliminado' => false, 'fecha_envio' => now()->subHours(1)],
            // Chat 3
            ['id_chat' => 3, 'id_usuario' => 4, 'contenido_texto' => 'El proyecto final consiste en diseñar una base de datos completa.', 'tipo_mensaje' => 'texto', 'eliminado' => false, 'fecha_envio' => now()->subDays(1)],
            ['id_chat' => 3, 'id_usuario' => 7, 'contenido_texto' => 'Profesor, ¿podemos hacer el proyecto en grupos?', 'tipo_mensaje' => 'texto', 'eliminado' => false, 'fecha_envio' => now()->subHours(12)],
            // Chat 4
            ['id_chat' => 4, 'id_usuario' => 7, 'contenido_texto' => 'Hola! ¿Alguien quiere estudiar para el examen de matemáticas?', 'tipo_mensaje' => 'texto', 'eliminado' => false, 'fecha_envio' => now()->subHours(5)],
            ['id_chat' => 4, 'id_usuario' => 8, 'contenido_texto' => 'Yo sí! ¿A qué hora nos podemos reunir?', 'tipo_mensaje' => 'texto', 'eliminado' => false, 'fecha_envio' => now()->subHours(4)],
        ];
        DB::table('mensajes')->insert($mensajes);

        // ==================== ENTREGAS DE TRABAJOS ====================
        $entregas = [
            [
                'id_estudiante' => 7,
                'id_unidad' => 1,
                'titulo' => 'Tarea 1: Algoritmos Básicos',
                'descripcion' => 'Ejercicios de algoritmos con pseudocódigo',
                'nombre_archivo' => 'tarea1_juan_perez.pdf',
                'ruta_archivo' => 'trabajos_estudiantes/tarea1_juan_perez.pdf',
                'estado' => 'revisado',
                'calificacion' => 18.50,
                'comentarios_profesor' => 'Excelente trabajo, muy bien explicado.',
                'fecha_entrega' => now()->subDays(5),
                'fecha_revision' => now()->subDays(2)
            ],
            [
                'id_estudiante' => 8,
                'id_unidad' => 1,
                'titulo' => 'Tarea 1: Algoritmos Básicos',
                'descripcion' => 'Ejercicios de algoritmos con pseudocódigo',
                'nombre_archivo' => 'tarea1_maria_gonzalez.pdf',
                'ruta_archivo' => 'trabajos_estudiantes/tarea1_maria_gonzalez.pdf',
                'estado' => 'pendiente',
                'calificacion' => null,
                'comentarios_profesor' => null,
                'fecha_entrega' => now()->subDays(3),
                'fecha_revision' => null
            ],
            [
                'id_estudiante' => 11,
                'id_unidad' => 3,
                'titulo' => 'Diseño de Base de Datos - Librería',
                'descripcion' => 'Modelo entidad-relación de sistema de librería',
                'nombre_archivo' => 'bd_libreria_miguel.pdf',
                'ruta_archivo' => 'trabajos_estudiantes/bd_libreria_miguel.pdf',
                'estado' => 'aprobado',
                'calificacion' => 19.00,
                'comentarios_profesor' => 'Muy buen diseño, cumple con todos los requisitos.',
                'fecha_entrega' => now()->subDays(10),
                'fecha_revision' => now()->subDays(7)
            ],
        ];
        DB::table('entregas_trabajos')->insert($entregas);

        echo "\n✅ Base de datos sembrada exitosamente!\n";
        echo "📊 Datos creados:\n";
        echo "   - 3 Roles\n";
        echo "   - 16 Usuarios (1 Admin, 5 Profesores, 10 Estudiantes)\n";
        echo "   - 6 Unidades Curriculares\n";
        echo "   - " . count($inscripciones) . " Inscripciones\n";
        echo "   - 4 Chats con " . count($participantes) . " participantes\n";
        echo "   - " . count($mensajes) . " Mensajes\n";
        echo "   - 3 Entregas de Trabajos\n\n";
        echo "🔑 Credenciales de acceso:\n";
        echo "   Admin:      admin@comunidad.com / admin123\n";
        echo "   Profesor:   carlos.gomez@comunidad.com / profesor123\n";
        echo "   Estudiante: juan.perez@estudiante.com / estudiante123\n";
    }
}