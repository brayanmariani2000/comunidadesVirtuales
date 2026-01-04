<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipanteChat extends Model
{
    use HasFactory;

    protected $table = 'participantes_chat';
    protected $primaryKey = 'id_participante';
    public $timestamps = false;

    protected $fillable = [
        'id_chat',
        'id_usuario',
        'rol_participante',
        'activo'
    ];

    protected $casts = [
        'fecha_union' => 'datetime',
        'activo' => 'boolean'
    ];

    // Relaciones
    public function chat()
    {
        return $this->belongsTo(Chat::class, 'id_chat');
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}