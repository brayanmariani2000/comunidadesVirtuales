<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    use HasFactory;

    protected $table = 'mensajes';
    protected $primaryKey = 'id_mensaje';
    public $timestamps = false;

    protected $fillable = [
        'id_chat',
        'id_usuario',
        'contenido_texto',
        'tipo_mensaje',
        'mensaje_respondido',
        'eliminado'
    ];

    protected $casts = [
        'fecha_envio' => 'datetime',
        'fecha_editado' => 'datetime',
        'eliminado' => 'boolean'
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

    public function respuestaA()
    {
        return $this->belongsTo(Mensaje::class, 'mensaje_respondido');
    }

    public function respuestas()
    {
        return $this->hasMany(Mensaje::class, 'mensaje_respondido');
    }

    public function documentos()
    {
        return $this->hasMany(DocumentoAdjunto::class, 'id_mensaje');
    }

    public function leidoPor()
    {
        return $this->belongsToMany(Usuario::class, 'mensajes_leidos', 'id_mensaje', 'id_usuario')
                    ->withPivot('fecha_lectura');
    }

    // Scopes
    public function scopeNoEliminados($query)
    {
        return $query->where('eliminado', false);
    }
}