<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonaMedio extends Model
{
    use HasFactory;
     protected $table = 'zona_medios';

    protected $fillable = [
        'zona_id',
        'tipo',
        'url',
        'descripcion',
    ];

    public function zona()
    {
        return $this->belongsTo(Zonas::class);
    }
}
