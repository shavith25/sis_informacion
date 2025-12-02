<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComunidadArchivo extends Model
{
    use HasFactory;

    protected $table = 'comunidad_archivos';

    protected $fillable = [
        'nombre',
        'titulo',
        'descripcion',
        'ruta_archivo',
        'mime_type',
        'aprobado'
    ];
}
