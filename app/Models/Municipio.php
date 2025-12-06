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
    
    /**
     * Obtener todos los medios asociados al municipio.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getRouteKey()
    {
        $rand = rand(10000, 99999);
        $mezcla = $this->getKey() ^ $rand; // XOR para ocultar
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
                if (count($partes) === 2) {
                    $aleatorio = hexdec($partes[0]);
                    $mezcla = hexdec($partes[1]);
                    $idReal = $mezcla ^ $aleatorio; 
                    
                    return $this->where('id', $idReal)->firstOrFail();
                }
            } catch (\Exception $e) {
                // Manejo de error si es necesario
            }
        }

        abort(404);
    }
}
