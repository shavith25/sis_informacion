<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provincia extends Model
{
    protected $table = 'provincias';

    protected $fillable = [
        'id_departamento',
        'tipo_geometria',
        'geometria',
        'nombre',
        'descripcion',
        
    ];

    
    protected $casts = [
        'geometria' => 'array',  
    ];
     public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'id_departamento');
    }

    /**
     * Una provincia tiene muchos municipios.
     */
    public function municipios()
    {
        return $this->hasMany(Municipio::class, 'id_provincia');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
