<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    
    protected $fillable = [
        'mediable_id',
        'mediable_type',
        'archivo',      // Ruta del archivo
        'tipo',         // imagen, video, documento
        'nombre',       // Nombre original
        'tamanio',      // Tamaño en bytes
        'extension',    // jpg, png, pdf, etc.
    ];

    /**
     * Relación polimórfica inversa
     */
    public function mediable()
    {
        return $this->morphTo();
    }

    /**
     * Obtener la URL completa del archivo
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->archivo);
    }

    /**
     * Obtener la URL de la miniatura (si existe)
     */
    public function getThumbnailUrlAttribute()
    {
        // Si tienes miniaturas generadas
        $path = str_replace('/', '/thumbs/', $this->archivo);
        if (file_exists(storage_path('app/public/' . $path))) {
            return asset('storage/' . $path);
        }
        return $this->url;
    }
}