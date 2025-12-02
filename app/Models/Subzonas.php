<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subzonas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'coordenadas',
        'estado',
        'zona_id',
    ];

    protected $casts = [
        'coordenadas' => 'array',
        'estado' => 'boolean',
        'imagenes' => 'array',
        'videos' => 'array',
    ];

    public function zona()
    {
        return $this->belongsTo(Zonas::class);
    }
}
