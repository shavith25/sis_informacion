<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonaEventoMedio extends Model
{
    use HasFactory;
     protected $table = 'zona_evento_medios';

    protected $fillable = [
        'evento_id',
        'tipo',
        'url',
        'descripcion',
    ];
}
