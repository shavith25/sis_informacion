<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'area',
        'descripcion',
        'estado',
    ];

    /**
     * Relación uno a muchos con Zonas.
     * Un área puede tener muchas zonas.
     */
    public function zonas()
    {
        return $this->hasMany(Zonas::class, 'area_id');
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
                if (count($partes) === 2) {
                    $aleatorio = hexdec($partes[0]);
                    $mezcla = hexdec($partes[1]);
                    $idReal = $aleatorio ^ $mezcla;

                    return $this->where('id', $idReal)->firstOrFail();
                }
            } catch (\Exception $e) {
                // Si falla, pasamos al error 404
            }
        }
        abort(404);
    }
}
