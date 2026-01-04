<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chats';
    protected $primaryKey = 'id_chat';
    public $timestamps = false;

    protected $fillable = [
        'nombre_chat',
        'descripcion',
        'id_unidad',
        'tipo_chat',
        'creado_por',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'fecha_creacion' => 'datetime'
    ];

    // Relaciones
    public function unidadCurricular()
    {
        return $this->belongsTo(UnidadCurricular::class, 'id_unidad');
    }

    public function creador()
    {
        return $this->belongsTo(Usuario::class, 'creado_por');
    }

    public function participantes()
    {
        return $this->hasMany(ParticipanteChat::class, 'id_chat');
    }

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class, 'id_chat')->orderBy('fecha_envio', 'asc');
    }

    public function ultimoMensaje()
    {
        return $this->hasOne(Mensaje::class, 'id_chat')->latest('fecha_envio');
    }

    public function usuariosParticipantes()
    {
        return $this->belongsToMany(Usuario::class, 'participantes_chat', 'id_chat', 'id_usuario')
                    ->wherePivot('activo', true);
    }
}