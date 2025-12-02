<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonaEvento extends Model
{
    use HasFactory;
     protected $fillable = [
        'titulo',
        'descripcion',
        'coordenadas',
        'estado',
        'zona_id',
        'tipo',
        'fecha_evento',
        'tipo_coordenada'
    ];

    protected $casts = [
        'coordenadas' => 'array',
        'estado' => 'boolean',
    ];
}
