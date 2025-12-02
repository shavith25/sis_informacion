<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documento extends Model
{
    use HasFactory;

    protected $fillable = [
        'icono',
        'titulo',
        'resumen',
        'fecha_publicacion',
        'fecha_emision',
        'numero_documento',
        'pdf',
    ];
}
