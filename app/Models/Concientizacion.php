<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class Concientizacion extends Model
{
    use HasFactory;

    protected $table = 'concientizaciones';

    protected $fillable = [
        'titulo',
        'descripcion',
        'video_path',
        'categoria',
    ];

    public function getRouteKey()
    {
        $encrypted = Crypt::encryptString($this->getKey());
        return str_replace(['/', '+'], ['_', '-'], $encrypted);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        try {
            $id = Crypt::decryptString($value);

            return $this->where('id', $id)->firstOrFail();

        } catch (DecryptException $e) {
            abort(404);
        }
    }
}
