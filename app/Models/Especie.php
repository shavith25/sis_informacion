<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especie extends Model
{
    use HasFactory;

    protected $fillable = ['titulo', 'descripcion', 'tipo', 'zona_id'];

    public function imagenes()
    {
        return $this->hasMany(EspecieImagen::class, 'especie_id');
    } 

    public function zona()
    {
        return $this->belongsTo(Zonas::class, 'zona_id');
    }
    
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function getRouteKey()
    {
        $rand = rand(10000, 99999);
        $mezcla = $rand ^ $this->getKey();
        
        return dechex($rand) . 'x' . dechex($mezcla);
    }

    /**
     * Lógica Híbrida:
     * 1. Acepta números (Admin): /especies/23/edit
     * 2. Acepta tokens (Público): /detalle/especie/a1b2x9988
     */
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
                // Si la matemática falla, abortamos
            }
        }

        abort(404);
    }
}