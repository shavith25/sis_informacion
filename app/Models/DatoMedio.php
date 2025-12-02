<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatoMedio extends Model
{
     use HasFactory;

    protected $table = 'dato_medios';

    protected $fillable = [
        'dato_id',
        'tipo',
        'path',
        'descripcion',
    ];

    public function dato()
    {
        return $this->belongsTo(Dato::class);
    }
}
