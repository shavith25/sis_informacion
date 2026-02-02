<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
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

    /**
     * Verifica si el usuario está activo basado en su estado.
     *
     * @return bool
     */
    public function estaActivo()
    {
        return (int) $this->estado === 1;
    }

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
            $idReal = Crypt::decryptString($value);

            return $this->where('id', $idReal)->firstOrFail();
        } catch (DecryptException $e) {
            abort(404);
        }
    }
}