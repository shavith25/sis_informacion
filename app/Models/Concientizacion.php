<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concientizacion extends Model
{
    use HasFactory;

    protected $table = 'concientizaciones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'video_path',
        'categoria',
    ];

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
                    $idReal = $mezcla ^ $aleatorio;
                    
                    return $this->where('id', $idReal)->firstOrFail();
                }
            } catch (\Exception $e) {}
        }

        abort(404);
    }
}
