<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';
    protected $primaryKey = 'id_estudiante';

    protected $fillable = [
        'id_estudiante',
        'matricula',
        'fecha_ingreso'
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_estudiante', 'id_usuario');
    }

    public function inscripciones()
    {
        return $this->hasMany(InscripcionEstudiante::class, 'id_estudiante');
    }
}