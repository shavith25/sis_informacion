<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ComentarioParticipacion extends Model
{
    use HasFactory;

    protected $table = 'comentario_participaciones';

    protected $fillable = [
        'nombre',
        'comentario',
        'parent_id',
        'likes',
        'aprobado',
    ];

    public function respuestas()
    {
        return $this->hasMany(ComentarioParticipacion::class, 'parent_id');
    }

    public function respuestasAprobadas()
    {
        return $this->hasMany(ComentarioParticipacion::class, 'parent_id')->where('aprobado', true);
    }

    public function parent()
    {
        return $this->belongsTo(ComentarioParticipacion::class, 'parent_id');
    }
}
