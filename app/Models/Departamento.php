<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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
