<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadCurricular extends Model
{
    use HasFactory;

    protected $table = 'unidades_curriculares';
    protected $primaryKey = 'id_unidad';

    protected $fillable = [
        'codigo_unidad',
        'nombre_unidad',
        'descripcion',
        'creditos',
        'activa'
    ];

    public function inscripciones()
    {
        return $this->hasMany(InscripcionEstudiante::class, 'id_unidad');
    }

    public function estudiantes()
    {
        return $this->belongsToMany(Usuario::class, 'inscripciones_estudiantes', 'id_unidad', 'id_estudiante')
                    ->where('id_rol', 3); // Solo estudiantes
    }

    public function profesores()
    {
        return $this->belongsToMany(Usuario::class, 'asignacion_profesores', 'id_unidad', 'id_profesor')
                    ->where('id_rol', 2); // Solo profesores
    }
}