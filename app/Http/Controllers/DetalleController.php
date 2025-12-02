<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Zonas;
use App\Models\Datos;
use App\Models\Noticia;
use App\Models\Especie;
class DetalleController extends Controller
{
    public function show($tipo, $id)
        {
            switch ($tipo) {
                case 'zona':
                    $item = Zonas::with('imagenes', 'area', 'videos', 'historial','especies')->findOrFail($id);
                    $view = 'zonas.publico.detalle-zona';
                    break;

                case 'dato':
                    $item = Datos::with('imagenes','medios', 'zona')->findOrFail($id);
                    $view = 'zonas.publico.detalle-dato';
                    break;

                case 'especie':
                    $item = Especie::with('imagenes')->findOrFail($id);
                    $view = 'zonas.publico.detalle-especie';
                    break;

                case 'noticia':
                    $item = Noticia::with('imagenes')->findOrFail($id);
                    $view = 'zonas.publico.detalle-noticia';
                    break;

                default:
                    abort(404);
            }

            $zonas = Zonas::with([
                'imagenes',
                'videos',
                'area',
                'historial' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                }
            ])->get();

            $zonasActivas = $zonas->where('estado', true)->count();

            return view($view, compact('item', 'tipo', 'zonasActivas'));
        }

}
