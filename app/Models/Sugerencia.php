<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sugerencia extends Model
{
    use HasFactory;

    protected $table = 'sugerencias';

    protected $fillable = [
        'nombre',
        'titulo',
        'contenido',
        'aprobado'
    ];

    protected $casts = [
        'aprobado' => 'boolean',
    ];

    protected $attributes = [
        'aprobado' => false,
    ];
}
