<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\Municipio;
use App\Models\Provincia;
use Illuminate\Database\QueryException;

class MunicipioController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-municipio|crear-municipio|editar-municipio|borrar-municipio', ['only' => ['index']]);
        $this->middleware('permission:crear-municipio', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-municipio', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-municipio', ['only' => ['destroy']]);
    }

    public function index()
    {
        $municipios = Municipio::with('provincia')->paginate(60);
        return view('limites.municipios.index', compact('municipios'));
    }

    public function create()
    {
        $provincias = Provincia::select('id', 'nombre')->get();
        return view('limites.municipios.create', compact('provincias'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'id_provincia'    => 'required|exists:provincias,id',
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'geometria'       => 'nullable|json',
            'media.*'         => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi',
        ]);

        $municipioData = $validatedData;
        $municipioData['geometria'] = !empty($validatedData['geometria']) ? json_decode($validatedData['geometria'], true) : null;
        $municipioData['tipo_geometria'] = !empty($validatedData['geometria']) ? 'geojson' : null;

        try {
            $municipio = Municipio::create($municipioData);
        } catch (QueryException $e) {
            if ($e->getCode() == '23505') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Error: Ha ocurrido un conflicto de datos duplicados.');
            }
            throw $e;
        }

        // Manejar archivos multimedia si existen
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $archivo) {
                $path = $archivo->store('municipios', 'public');
                $municipio->media()->create([
                    'archivo' => $path,
                    'tipo' => $archivo->getClientMimeType()
                ]);
            }
        }

        return redirect()->route('limites.municipios.index')->with('success', 'Municipio creado correctamente.');
    }

    public function edit(Municipio $municipio)
    {
        $provincias = Provincia::select('id', 'nombre')->get();
        
        // No es necesario decodificar si usas casts en el modelo, pero lo dejo por si acaso no funciona el cast automático
        if (is_string($municipio->geometria)) {
            $municipio->geometria = json_decode($municipio->geometria, true);
        }

        return view('limites.municipios.edit', compact('municipio', 'provincias'));
    }

    public function update(Request $request, Municipio $municipio)
    {
        $request->validate([
            'id_provincia'    => 'required|exists:provincias,id',
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'geometria'       => 'nullable|json', // Cambiado a nullable para ser consistente con create
            'media.*'         => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi',
        ]);

        $geometria = !empty($request->geometria) ? json_decode($request->geometria, true) : null;

        $municipio->update([
            'id_provincia'    => $request->id_provincia,
            'nombre'          => $request->nombre,
            'descripcion'     => $request->descripcion,
            'tipo_geometria'  => !empty($request->geometria) ? 'geojson' : null,
            'geometria'       => $geometria,
        ]);

        if ($request->has('delete_media')) {
            $medias = Media::whereIn('id', $request->delete_media)->get();
            foreach ($medias as $media) {
                $media->delete();
            }
        }

        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $archivo) {
                $path = $archivo->store('municipios', 'public');
                $municipio->media()->create([
                    'archivo' => $path,
                    'tipo' => $archivo->getClientMimeType()
                ]);
            }
        }

        return redirect()->route('limites.municipios.index')->with('success', 'Municipio actualizado correctamente.');
    }

    public function destroy(Municipio $municipio)
    {
        foreach ($municipio->media as $media) {
            $media->delete();
        }

        $municipio->delete();

        return redirect()->route('limites.municipios.index')->with('success', 'Municipio eliminado correctamente.');
    }

    public function destroyMedia(Media $media)
    {
        $media->delete();
        return back()->with('success', 'Medio eliminado correctamente.');
    }
}