<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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

    /*public function toggleEstado(): self
    {
        $this->estado = ! (bool) $this->estado;
        $this->save();

        return $this->fresh();
    }*/
}
