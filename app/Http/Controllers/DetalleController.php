<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zonas;
use App\Models\Datos;
use App\Models\Noticia;
use App\Models\Especie;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class DetalleController extends Controller
{
    public function show($tipo, $id)
    {
        $idReal = null;

        if (is_numeric($id)) {
            $idReal = $id;
        } 
        else {
            try {
                // Intentar desencriptar con el facade Crypt de Laravel
                $idReal = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                // Si falla la desencriptación de Laravel, intentar con el método personalizado 'x'
                if (strpos($id, 'x') !== false) {
                    try {
                        $partes = explode('x', $id);
                        if(count($partes) == 2) {
                            $aleatorio = hexdec($partes[0]);
                            $mezcla = hexdec($partes[1]);
                            $idReal = $mezcla ^ $aleatorio;
                        }
                    } catch (\Exception $e) {
                        // Falló la matemática, $idReal permanece null
                    }
                }
            }
        }

        if (!$idReal) {
            abort(404);
        }
            $item = null;
            $view = '';

        // Cargar el modelo correspondiente según el tipo
        try {
            switch ($tipo) {
                case 'zona':
                    $item = Zonas::with('imagenes', 'area', 'videos', 'historial','especies')->findOrFail($idReal);
                    $view = 'zonas.publico.detalle-zona';
                    break;

                case 'dato':
                    $item = Datos::with('imagenes','medios', 'zona')->findOrFail($idReal);
                    $view = 'zonas.publico.detalle-dato';
                    break;

                case 'especie':
                    $item = Especie::with('imagenes')->findOrFail($idReal);
                    $view = 'zonas.publico.detalle-especie';
                    break;

                case 'noticia':
                    $item = Noticia::with('imagenes')->findOrFail($idReal);
                    $view = 'zonas.publico.detalle-noticia';
                    break;

                default:
                    abort(404);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404); 
        }

        $zonas = Zonas::with(['imagenes', 'videos', 'area', 'historial' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();

        $zonasActivas = $zonas->where('estado', true)->count();

        return view($view, compact('item', 'tipo', 'zonasActivas'));
    }
}