<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoAyuda extends Model
{
    use HasFactory;

    protected $table = 'documentos_ayuda';

    protected $fillable = [
        'titulo',
        'autor',
        'nombre_archivo',
        'ruta_archivo',
        'tamano_bytes',
        'tipo',
        'descargas',
    ];

    public function getSizeMbAttribute()
    {
        return round($this->tamano_bytes / 1024 / 1024, 2);
    }
}
