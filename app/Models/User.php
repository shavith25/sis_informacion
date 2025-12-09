<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

// 1. IMPORTANTE: Importar estas clases
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'url_image', 'estado',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'estado' => 'boolean',
    ];

    // =======================================================================
    // NUEVA LÓGICA DE ENCRIPTACIÓN SEGURA (LARAVEL CRYPT)
    // =======================================================================

    /**
     * Al generar una URL (route('usuarios.edit', $user)), Laravel llamará a esto.
     * Devuelve una cadena larga encriptada en lugar del ID.
     */
    public function getRouteKey()
    {
        return Crypt::encryptString($this->getKey());
    }

    /**
     * Al recibir una URL entrante (/usuarios/{encrypted_id}), Laravel llamará a esto.
     * Desencripta la cadena para encontrar el ID real en la BD.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        try {
            // Intentamos desencriptar
            $idReal = Crypt::decryptString($value);
            
            // Buscamos el usuario por el ID desencriptado
            return $this->where('id', $idReal)->firstOrFail();
        } catch (DecryptException $e) {
            // Si el código no es válido o fue alterado, damos error 404
            abort(404);
        }
    }
}