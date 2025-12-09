<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ZonaMedio;
use App\Models\Especie;
use Illuminate\Support\Facades\Crypt; 
use Illuminate\Contracts\Encryption\DecryptException;

class Zonas extends Model

{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'descripcion',
        'coordenadas',
        'estado',
        'area_id',
        'tipo_coordenada'
    ];

    protected $casts = [
        'coordenadas' => 'array',
        'estado' => 'boolean',
    ];

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

    public function medios()
    {
        return $this->hasMany(ZonaMedio::class, 'zona_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function imagenes()
    {
        return $this->hasMany(ZonaMedio::class, 'zona_id')->where('tipo', 'imagen');
    }

    public function videos()
    {
        return $this->hasMany(ZonaMedio::class, 'zona_id')->where('tipo', 'video');
    }

    public function datos()
    {
        return $this->hasOne(Datos::class, 'zona_id');
    }

    public function historial()
    {
        return $this->hasMany(ZonaHistorial::class, 'zona_id')->orderBy('created_at', 'desc');
    }

    public function eventos()
    {
        return $this->hasMany(ZonaEvento::class, 'zona_id')->orderBy('created_at', 'desc');
    }

    public function ultimoHistorial()
    {
        return $this->hasOne(ZonaHistorial::class, 'zona_id')->latestOfMany();
    }

    public function getCoordenadasLeaflet()
    {
        if (!$this->coordenadas || !is_array($this->coordenadas)) {
            return [];
        }

        $result = [];

        foreach ($this->coordenadas as $item) {
            if (!isset($item['tipo'])) continue;

            if ($item['tipo'] === 'marcador') {
                $result[] = [
                    'type' => 'marker',
                    'lat' => $item['coordenadas']['lat'],
                    'lng' => $item['coordenadas']['lng']
                ];
            } elseif ($item['tipo'] === 'poligono') {
                $points = [];
                foreach ($item['coordenadas'] as $coord) {
                    $points[] = [$coord['lat'], $coord['lng']];
                }
                $result[] = [
                    'type' => 'polygon',
                    'points' => $points
                ];
            }
        }

        return $result;
    }
    
    public function especies()
    {
        return $this->hasMany(Especie::class, 'zona_id');
    }
}
