<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SesionUsuario extends Model
{
    use HasFactory;

    protected $table = 'sesiones_usuarios';
    protected $primaryKey = 'id_sesion';
    public $timestamps = false; // IMPORTANTE: No usa created_at/updated_at

    protected $fillable = [
        'id_usuario',
        'token_sesion',
        'ip_conexion',
        'dispositivo_info',
        'activa'
        // fecha_inicio y fecha_expiracion se manejan automáticamente
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_ultima_actividad' => 'datetime',
        'fecha_expiracion' => 'datetime',
        'activa' => 'boolean'
    ];

    // Relación con usuario
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }

    /**
     * Crear una nueva sesión de forma segura
     */
    public static function crearSesion($id_usuario, $token, $ip, $dispositivo)
    {
        // 1. Primero desactivar sesiones anteriores
        self::where('id_usuario', $id_usuario)
            ->where('activa', true)
            ->update(['activa' => false]);
        
        // 2. Crear nueva sesión
        return self::create([
            'id_usuario' => $id_usuario,
            'token_sesion' => $token,
            'ip_conexion' => $ip,
            'dispositivo_info' => $dispositivo,
            'activa' => true,
            // MySQL establecerá automáticamente:
            // - fecha_inicio (default current_timestamp)
            // - fecha_ultima_actividad (default current_timestamp)
            // - fecha_expiracion (default + 1 day por trigger, ahora lo hacemos manual)
            'fecha_expiracion' => now()->addDay() // Establecer manualmente
        ]);
    }

    /**
     * Verificar si la sesión está expirada
     */
    public function isExpirada()
    {
        return now()->greaterThan($this->fecha_expiracion);
    }

    /**
     * Actualizar última actividad
     */
    public function actualizarActividad()
    {
        $this->update([
            'fecha_ultima_actividad' => now()
        ]);
    }

    /**
     * Cerrar sesión
     */
    public function cerrar()
    {
        $this->update(['activa' => false]);
    }
}