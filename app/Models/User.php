<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;


use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * Atributos que se pueden asignar masivamente
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'url_image',
        'estado',
    ];

    /**
     * Atributos ocultos al convertir a JSON
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos de datos
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'estado' => 'boolean',
    ];

    /**
     * Atributos que deben ser ocultados para arrays
     */
    // Genera un código Hexadecimal basado en el ID del usuario
    public function getRouteKey()
    {
        $idOculto = $this->getKey() ^ 123456789; // Máscara XOR
        return dechex($idOculto);
    }

    /**
     * Resuelve la vinculación de ruta utilizando el ID oculto
     */
    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $idReal = hexdec($value) ^ 123456789; // Máscara XOR inversa
            return $this->where('id', $idReal)->firstOrFail();
        } catch (\Exception $e) {
            abort(404);
        }
    }
}