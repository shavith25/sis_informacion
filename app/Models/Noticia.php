<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Noticia extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'subtitulo',
        'descripcion',
        'autor',
        'fecha_publicacion',
    ];

    protected $casts = [
        'fecha_publicacion' => 'date',
    ];
    
    public function imagenes()
    {
        return $this->hasMany(NoticiaImagen::class);
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
                    
                    $idReal = $mezcla ^ $aleatorio; 
                    
                    return $this->where('id', $idReal)->firstOrFail();
                }
            } catch (\Exception $e) {
                // Si falla la matemática, ignoramos
            }
        }

        abort(404);
    }
}
