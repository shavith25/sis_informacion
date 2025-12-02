<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoticiaImagen extends Model
{
    use HasFactory;
    protected $table = 'noticia_imagenes';
    protected $fillable = [
        'noticia_id',
        'ruta',
        'descripcion',
    ];

    public function noticia()
    {
        return $this->belongsTo(Noticia::class);
    }
}
