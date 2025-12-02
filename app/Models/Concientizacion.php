<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concientizacion extends Model
{
    use HasFactory;

    protected $table = 'concientizaciones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'video_path',
        'categoria',
    ];
}
