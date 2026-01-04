<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoAdjunto extends Model
{
    use HasFactory;

    protected $table = 'documentos_adjuntos';
    protected $primaryKey = 'id_documento';
    public $timestamps = false;

    protected $fillable = [
        'id_mensaje',
        'nombre_archivo',
        'tipo_archivo',
        'tamano_archivo',
        'ruta_almacenamiento'
    ];

    protected $casts = [
        'tamano_archivo' => 'integer',
        'fecha_subida' => 'datetime'
    ];

    // Relaciones
    public function mensaje()
    {
        return $this->belongsTo(Mensaje::class, 'id_mensaje');
    }

    // Métodos útiles
    public function getTamanoFormateadoAttribute()
    {
        $bytes = $this->tamano_archivo;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    public function getIconoAttribute()
    {
        $extension = strtolower(pathinfo($this->nombre_archivo, PATHINFO_EXTENSION));
        
        $iconos = [
            'pdf' => 'bi-file-earmark-pdf',
            'doc' => 'bi-file-earmark-word',
            'docx' => 'bi-file-earmark-word',
            'xls' => 'bi-file-earmark-excel',
            'xlsx' => 'bi-file-earmark-excel',
            'ppt' => 'bi-file-earmark-ppt',
            'pptx' => 'bi-file-earmark-ppt',
            'jpg' => 'bi-file-earmark-image',
            'jpeg' => 'bi-file-earmark-image',
            'png' => 'bi-file-earmark-image',
            'gif' => 'bi-file-earmark-image',
            'zip' => 'bi-file-earmark-zip',
            'rar' => 'bi-file-earmark-zip',
        ];
        
        return $iconos[$extension] ?? 'bi-file-earmark';
    }
}