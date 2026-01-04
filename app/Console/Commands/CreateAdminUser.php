<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Usuario;
use App\Models\Rol;
use Illuminate\Support\Facades\Hash; // ¡Añade esta línea!

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin 
                           {--name= : Nombre del administrador}
                           {--email= : Correo electrónico}
                           {--password= : Contraseña}';
    
    protected $description = 'Crear usuario administrador desde consola';

    public function handle()
    {
        $this->info('🔄 Creando usuario administrador...');
        
        // Obtener valores
        $nombre = $this->option('name') ?? $this->ask('Nombre del administrador', 'Administrador');
        $apellido = $this->ask('Apellido del administrador', 'Sistema');
        $cedula = $this->ask('Cédula', '00000001');
        $correo = $this->option('email') ?? $this->ask('Correo electrónico', 'admin@universidad.edu');
        $password = $this->option('password') ?? $this->ask('Contraseña', 'admin123');
        
        // Verificar rol
        $rolAdmin = Rol::firstOrCreate(
            ['id_rol' => 1],
            [
                'nombre_rol' => 'administrador',
                'descripcion' => 'Administrador del sistema'
            ]
        );
        
        // Verificar si existe
        if (Usuario::where('correo', $correo)->exists()) {
            $this->error('❌ El usuario con ese correo ya existe');
            return 1;
        }
        
        // Crear usuario con contraseña hasheada
        $admin = Usuario::create([
            'id_rol' => 1,
            'nombre' => $nombre,
            'apellido' => $apellido,
            'cedula' => $cedula,
            'telefono' => '0000000000',
            'correo' => $correo,
            'contrasena_hash' => Hash::make($password), // ¡Hashear aquí!
            'activo' => 1,
            'fecha_registro' => now(),
            'ultimo_acceso' => null
        ]);
        
        $this->info('✅ Usuario administrador creado exitosamente!');
        $this->line('📋 Datos del usuario:');
        $this->table(
            ['Campo', 'Valor'],
            [
                ['ID', $admin->id_usuario],
                ['Nombre', $admin->nombre . ' ' . $admin->apellido],
                ['Cédula', $admin->cedula],
                ['Correo', $admin->correo],
                ['Contraseña', $password . ' (texto plano)'], // Mostrar contraseña en texto plano solo para referencia
                ['Rol', 'Administrador'],
                ['Fecha Registro', $admin->fecha_registro]
            ]
        );
        
        return 0;
    }
}