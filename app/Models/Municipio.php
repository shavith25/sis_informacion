<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
     use HasFactory;

    protected $table = 'municipios';

    protected $fillable = [
        'id_provincia',
        'nombre',
        'geometria',
        'tipo_geometria',
        'descripcion',
    ];
    
    protected $casts = [
        'geometria' => 'array',  
    ];
    /**
     * Un municipio pertenece a una provincia.
     */
    public function provincia()
    {
        return $this->belongsTo(Provincia::class, 'id_provincia');
    }
    
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
