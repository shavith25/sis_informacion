<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonaHistorial extends Model
{
    use HasFactory;

    protected $table = 'zonas_historial';

    protected $fillable = [
        'zona_id',
        'coordenadas',
        'tipo_coordenada',
        'imagen_mapa',
    ];

    protected $casts = [
        'coordenadas' => 'array'
    ];

    public function zona()
    {
        return $this->belongsTo(Zonas::class, 'zona_id'); 
    }
}
