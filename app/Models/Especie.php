<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

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
        $encrypted = Crypt::encryptString($this->getKey());
        return str_replace(['/', '+'], ['_', '-'], $encrypted);
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