<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'area',
        'descripcion',
        'estado',
    ];

    /**
     * Relación uno a muchos con Zonas.
     * Un área puede tener muchas zonas.
     */
    public function zonas()
    {
        return $this->hasMany(Zonas::class, 'area_id');
    }
}
