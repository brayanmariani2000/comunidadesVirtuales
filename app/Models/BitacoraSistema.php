<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraSistema extends Model
{
    use HasFactory;

    protected $table = 'bitacora_sistema';
    protected $primaryKey = 'id_bitacora';
    
    // Desactivar timestamps
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'accion',
        'modulo',
        'descripcion',
        'direccion_ip',
        'nivel',
        'fecha_hora'
    ];

    /**
     * Registrar una acción en el sistema
     */
    public static function registrar($accion, $modulo, $descripcion = null, $nivel = 'info')
    {
        return self::create([
            'id_usuario' => auth()->id(),
            'accion' => $accion,
            'modulo' => $modulo,
            'descripcion' => $descripcion,
            'direccion_ip' => request()->ip(),
            'nivel' => $nivel,
            'fecha_hora' => now()
        ]);
    }

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}