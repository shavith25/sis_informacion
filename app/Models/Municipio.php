<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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
