<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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

    /**
     * Una provincia pertenece a un departamento.
     */
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

    /**
     * Obtener todos los medios asociados a la provincia.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getRouteKey()
    {
        return Crypt::encryptString($this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $idReal = Crypt::decryptString($value);
            return $this->where('id', $idReal)->firstOrFail();
        } catch (DecryptException $e) {
            abort(404);
        }
    }
}
