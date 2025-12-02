<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
     use HasFactory;

    protected $fillable = [
        'titulo',
        'subtitulo',
        'descripcion',
        'autor',
        'fecha_publicacion',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
    ];
    
    public function imagenes()
    {
        return $this->hasMany(NoticiaImagen::class);
    }
}
