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

    public function getRouteKey()
    {
        $rand = rand(10000, 99999);
        $mezcla = $this->getKey() ^ $rand;

        return dechex($rand) . 'x' . dechex($mezcla);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        if(is_numeric($value)) {
            return $this->where('id', $value)->firstOrFail();
        }

        if(str_contains($value, 'x')) {
            try {
                $partes = explode('x', $value);
                if(count($partes) === 2) {
                    $aleatorio = hexdec($partes[0]);
                    $mezcla = hexdec($partes[1]);
                    $idReal = $mezcla ^ $aleatorio;

                    return $this->where('id', $idReal)->firstOrFail();
                }
            } catch (\Exception $e) {
                return null;
            }
        }

        abort(404);
    }
}
