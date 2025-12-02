<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;
use App\Models\Municipio;
use App\Models\Provincia;


class MunicipioController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-municipio|crear-municipio|editar-municipio|borrar-municipio', ['only' => ['index']]);
        $this->middleware('permission:crear-municipio', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-municipio', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-municipio', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $municipios = Municipio::with('provincia')->paginate(60);
        return view('limites.municipios.index',compact('municipios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $provincias = Provincia::select('id', 'nombre')->get();
        return view('limites.municipios.create', compact('provincias'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([

            'id_provincia'    => 'required|exists:provincias,id',
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'geometria'       => 'nullable|json',
            'media.*'         => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi',
        ],
        [
            'id_provincia.required' => 'Debe seleccionar una provincia para el municipio.',
            'nombre.required'       => 'El campo nombre es obligatorio.',
            'geometria.json'        => 'El formato de la geometría no es válido.',
            'media.*.mimes'         => 'Solo se permiten archivos de tipo: jpg, jpeg, png, gif, mp4, mov, avi.'
        ]);

        // Usar solo los datos validados para la creación
        $municipioData = $validatedData;
        // decodificar la geometria que viene en formato json
        $municipioData['geometria'] = !empty($validatedData['geometria']) ? json_decode($validatedData['geometria'], true) : null;
        $municipioData['tipo_geometria'] = !empty($validatedData['geometria']) ? 'geojson' : null;

        try {
            $municipio = Municipio::create($municipioData);
        } catch (\Illuminate\Database\QueryException $e) {
            // El código '23505' es específico de PostgreSQL para violaciones de unicidad.
            if ($e->getCode() == '23505') {
                // Redirige de vuelta con un mensaje de error amigable.
                return redirect()->back()
                    ->withInput() // Mantiene los datos del formulario que el usuario ya llenó.
                    ->with('error', 'Error: Ha ocurrido un conflicto de ID en la base de datos. Esto puede deberse a una desincronización. Por favor, intente guardar de nuevo. Si el problema persiste, contacte al administrador.');
            }

            // Si es otro tipo de error de base de datos, lo relanza.
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Municipio $municipio)
    {
        $provincias = Provincia::select('id', 'nombre')->get();
        return view('limites.municipios.edit', compact('municipio', 'provincias'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Municipio $municipio)
    {

        $request->validate([

            'id_provincia'    => 'required|exists:provincias,id',
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'geometria'       => 'nullable|json',
            'media.*'         => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi',
            ],
            [
                'id_provincia.required' => 'Debe seleccionar una provincia para el municipio.',
                'nombre.required'       => 'El campo nombre es obligatorio.',
                'geometria.json'        => 'El formato de la geometría no es válido.',
                'media.*.mimes'         => 'Solo se permiten archivos de tipo: jpg, jpeg, png, gif, mp4, mov, avi.'
            ]
        );

        $geometria = !empty($request->geometria) ? json_decode($request->geometria, true) : null;

        $request->validate([
            'id_provincia'    => 'required|exists:provincias,id',
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'geometria'       => 'required|json', 
            'media.*'         => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi',
        ]);

        $geometria = json_decode($request->geometria, true);


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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
