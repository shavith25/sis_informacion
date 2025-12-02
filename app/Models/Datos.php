<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Datos extends Model
{
    use HasFactory;

    protected $fillable = [
        'zona_id',
        'flora_fauna',
        'extension',
        'poblacion',
        'provincia',
        'especies_peligro',
        'otros_datos',
    ];

    public function zona()
    {
        return $this->belongsTo(Zonas::class, 'zona_id');
    }
    
    public function imagenes()
    {
        return $this->hasMany(DatoImagen::class, 'dato_id');
    }
    public function medios()
    {
        return $this->hasMany(DatoMedio::class, 'dato_id');
    }
}
