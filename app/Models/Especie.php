<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descripcion', 'tipo','zona_id'];

    public function imagenes()
    {
        return $this->hasMany(EspecieImagen::class, 'especie_id');
    } 

    public function zona()
    {
        return $this->belongsTo(Zonas::class, 'zona_id');
    }
    
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
