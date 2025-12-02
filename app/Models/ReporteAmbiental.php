<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteAmbiental extends Model
{
    use HasFactory;

    protected $table = 'reportes_ambientales';

    protected $fillable = [
        'nombre',
        'titulo',
        'contenido',
        'aprobado',
    ];

    protected $casts = [
        'aprobado' => 'boolean',
    ];
}
