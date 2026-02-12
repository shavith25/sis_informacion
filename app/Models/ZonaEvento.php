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
        'fecha_evento' => 'datetime',
    ];

    public function zona()
    {
        return $this->belongsTo(Zonas::class, 'zona_id');
    }

    public function medios()
    {
        return $this->hasMany(ZonaEventoMedio::class, 'evento_id');
    }

    public function imagenes()
    {
        return $this->medios()->where('tipo', 'imagen');
    }

    public function videos()
    {
        return $this->medios()->where('tipo', 'video');
    }
}
