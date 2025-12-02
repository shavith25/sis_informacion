<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    protected $table = 'departamentos';
    protected $fillable = [
        'nombre',
        'tipo_geometria',
        'geometria',
        'descripcion',
        
    ];

    
    protected $casts = [
        'geometria' => 'array',  
    ];

    public function provincias()
    {
        return $this->hasMany(Provincia::class, 'id_departamento');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
}
