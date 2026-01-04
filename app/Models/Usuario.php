<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    
    // DESACTIVAR TIMESTAMPS AUTOMÁTICOS
    public $timestamps = false;

    protected $fillable = [
        'id_rol',
        'nombre',
        'apellido',
        'cedula',
        'telefono',
        'correo',
        'contrasena_hash',
        'activo',
        'fecha_registro', // Tu campo personalizado
        'ultimo_acceso'   // Tu campo personalizado
    ];

    protected $hidden = [
        'contrasena_hash',
        'remember_token',
    ];

    // Cambiar el campo de contraseña por defecto
    public function getAuthPassword()
    {
        return $this->contrasena_hash;
    }

    // ... el resto del código se mantiene igual
    public function rol()
    {
        return $this->belongsTo(Rol::class, 'id_rol');
    }

    public function estudiante()
    {
        return $this->hasOne(Estudiante::class, 'id_estudiante', 'id_usuario');
    }

    public function profesor()
    {
        return $this->hasOne(Profesor::class, 'id_profesor', 'id_usuario');
    }

    public function sesiones()
    {
        return $this->hasMany(SesionUsuario::class, 'id_usuario');
    }

    // Scope para usuarios activos
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    // Método para obtener nombre completo
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombre} {$this->apellido}";
    }

    // Método para verificar si es administrador
 public function esAdministrador()
{
    return $this->id_rol === 1;
}

// Método para verificar si es profesor
public function esProfesor()
{
    return $this->id_rol === 2;
}

// Método para verificar si es estudiante
public function esEstudiante()
{
    return $this->id_rol === 3;
}
}