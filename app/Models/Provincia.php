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
        $rand = rand(10000, 99999);
        $mezcla = $this->getKey() ^ $rand;
        return dechex($rand) . 'x' . dechex($mezcla);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if (is_numeric($value)) {
            return $this->where('id', $value)->firstOrFail();
        }

        if (str_contains($value, 'x')) {
            try {
                $partes = explode('x', $value);
                if(count($partes) === 2) {
                    $aleatorio = hexdec($partes[0]);
                    $mezcla = hexdec($partes[1]);
                    $idReal = $mezcla ^ $aleatorio;
                    return $this->where('id', $idReal)->firstOrFail();
                }
            } catch (\Exception $e){}
        }

        abort(404);
    }
}
