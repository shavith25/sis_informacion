<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DatoImagen extends Model
{
    use HasFactory;

    protected $table = 'dato_imagenes';
    protected $fillable = ['dato_id', 'path', 'descripcion'];

    public function dato()
    {
        return $this->belongsTo(datos::class);
    }
}
