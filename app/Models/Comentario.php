<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $table = 'comentarios';

    protected $fillable = [
        'nombre',
        'comentario',
        'parent_id',
        'aprobado'
    ];

    public function respuestas()
    {
        return $this->hasMany(Comentario::class, 'parent_id');
    }

    /**
     * Define la relación para obtener solo las respuestas APROBADAS de un comentario.
     * Esta es la relación que el frontend debe consumir.
     * Las ordena de la más antigua a la más nueva para una lectura cronológica.
     */
    public function respuestasAprobadas()
    {
        return $this->hasMany(Comentario::class, 'parent_id')->where('aprobado', true);
    }


    public function parentComentario()
    {
        return $this->belongsTo(Comentario::class, 'parent_id');
    }
}
