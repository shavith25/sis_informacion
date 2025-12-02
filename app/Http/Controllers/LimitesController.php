<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Municipio;

class LimitesController extends Controller
{
    function __construct()
    {
        // Proteger solo las rutas administrativas
        $this->middleware('permission:ver-limites')->except(['publico', 'getProvincias', 'getMunicipios', 'getDetalle']);
    }

    // Ruta administrativa (requiere permiso)
    public function index()
    {
        return view('limites.index');
    }

    // Ruta pública (sin permiso requerido)
    public function publico()
    {
        $departamentos = Departamento::select('id', 'nombre', 'geometria', 'descripcion')->get();
        return view('zonas.publico.limites', compact('departamentos'));
    }

    // Rutas públicas para carga dinámica
    public function getProvincias($departamento_id)
    {
        if (!is_numeric($departamento_id)) {
            return response()->json([], 404);
        }

        return Provincia::where('id_departamento', $departamento_id)
                        ->select('id', 'nombre')
                        ->get();
    }

    public function getMunicipios($provincia_id)
    {
        return Municipio::where('id_provincia', $provincia_id)->select('id', 'nombre')->get();
    }

    public function getDetalle($tipo, $id)
    {
        switch ($tipo) {
            case 'departamento':
                $item = Departamento::with('media')->findOrFail($id);
                break;
            case 'provincia':
                $item = Provincia::with('media')->findOrFail($id);
                break;
            case 'municipio':
                $item = Municipio::with('media')->findOrFail($id);
                break;
            default:
                abort(404);
        }

        return response()->json([
            'nombre' => $item->nombre,
            'descripcion' => $item->descripcion,
            'geometria' => $item->geometria,
            'media' => $item->media
        ]);
    }
}