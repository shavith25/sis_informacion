<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EspecieImagen extends Model
{
    use HasFactory;
    protected $table = 'especie_imagenes';
    protected $fillable = ['especie_id', 'url'];

    public function especie()
    {
        return $this->belongsTo(Especie::class, 'especie_id');
    }
}
