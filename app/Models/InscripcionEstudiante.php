<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InscripcionEstudiante extends Model
{
    use HasFactory;

    protected $table = 'inscripciones_estudiantes';
    protected $primaryKey = 'id_inscripcion';

    protected $fillable = [
        'id_estudiante',
        'id_unidad',
        'estado'
    ];

    public function estudiante()
    {
        return $this->belongsTo(Usuario::class, 'id_estudiante', 'id_usuario');
    }

    public function unidadCurricular()
    {
        return $this->belongsTo(UnidadCurricular::class, 'id_unidad');
    }
}