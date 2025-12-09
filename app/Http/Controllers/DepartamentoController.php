<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use App\Models\Provincia;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Area;
use App\Models\Zonas;

class DepartamentoController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-departamento|crear-departamento|editar-departamento|borrar-departamento', ['only' => ['index']]);
        $this->middleware('permission:crear-departamento', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-departamento', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-departamento', ['only' => ['destroy']]);
    }

    public function index()
    {
        $departamentos = Departamento::paginate(5);
        return view('limites.departamentos.index', compact('departamentos'));
    }

    public function create()
    {
        return view('limites.departamentos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'geometria' => 'required|json',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480'
        ]);

        $departamento = Departamento::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo_geometria' => 'geojson',
            'geometria' => json_decode($request->geometria, true)
        ]);

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $archivo) {
                $ruta = $archivo->store('departamentos', 'public');

                $tipo = str_starts_with($archivo->getMimeType(), 'video') ? 'video' : 'imagen';

                $departamento->media()->create([
                    'tipo' => $tipo,
                    'archivo' => $ruta
                ]);
            }
        }

        return redirect()->route('limites.departamentos.index')->with('success', 'Departamento creado correctamente.');
    }

    public function edit(Departamento $departamento)
    {
        if (is_string($departamento->geometria)) {
            $departamento->geometria = json_decode($departamento->geometria, true);
        }

        return view('limites.departamentos.edit', compact('departamento'));
    }

    public function update(Request $request, Departamento $departamento)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'geometria' => 'required|json',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi',
        ]);

        $data = json_decode($request->geometria, true);

        $departamento->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'tipo_geometria' => 'geojson',
            'geometria' => $data,
        ]);

        if ($request->has('delete_media')) {
            $medias = Media::whereIn('id', $request->delete_media)->get();
            foreach ($medias as $media) {
                $media->delete();
            }
        }
            
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('departamentos', 'public');
                $tipo = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';

                $departamento->media()->create([
                    'archivo' => $path,
                    'tipo' => $tipo,
                ]);
            }
        }

        return redirect()->route('limites.departamentos.index')
                        ->with('success', 'Departamento actualizado correctamente.');
    }

    public function mapa()
    {
        $departamentos = Departamento::select('id', 'nombre', 'geometria', 'tipo_geometria')->get();
        $areas = Area::all();
        $zonas = Zonas::with(['area', 'historial' => function($query) {
            $query->orderBy('created_at', 'desc');
        }, 'ultimoHistorial','imagenes','videos'])->where('estado', true)->get();
        return view('mapa-areas.index', [
            'departamentos' => $departamentos,
            'areas' => $areas,
            'zonas' => $zonas,
        ]);
    }

    public function getByDepartamento($id)
    {
        $departamento = Departamento::find($id);

        if (!$departamento) {
            return response()->json(['error' => 'Departamento no encontrado'], 404);
        }
        
        $provincias = DB::table('provincias')
            ->select([
                'id',
                'nombre',
                'tipo_geometria',
                'geometria'
            ])
            ->where('id_departamento', $id)
            ->get();


        return response()->json([
            'departamento_nombre' => $departamento->nombre,
            'departamento_geometria' => $departamento->geometria,
            'provincias' => $provincias
        ]);
    }

    public function destroy(Departamento $departamento)
    {
        if ($departamento->media->count() > 0) {
            foreach ($departamento->media as $media) {
                $media->delete();
            }
        }

        $departamento->delete();

        return redirect()->route('limites.departamentos.index')
                        ->with('success', 'Departamento eliminado correctamente.');
    }
}