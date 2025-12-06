<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\Departamento;
use App\Models\Media;
class ProvinciaController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-provincia|crear-provincia|editar-provincia|borrar-provincia', ['only' => ['index']]);
        $this->middleware('permission:crear-provincia', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-provincia', ['only' => ['edit', 'update']]);
        $this->middleware('permission:borrar-provincia', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $provincias = Provincia::with('departamento')->paginate(25);
        return view('limites.provincias.index',compact('provincias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $departamentos = Departamento::select('id', 'nombre')->get();
        return view('limites.provincias.create', compact('departamentos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'id_departamento' => 'required|exists:departamentos,id',
                'nombre'          => 'required|string|max:255',
                'descripcion'     => 'nullable|string',
                'geometria'       => 'nullable|json',
                'media.*'         => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi',
            ],
            [
                'id_departamento.required' => 'Debe seleccionar un departamento para la provincia.',
                'nombre.required'          => 'El campo nombre es obligatorio.',
                'geometria.json'           => 'El formato de la geometría no es válido.',
                'media.*.mimes'            => 'Solo se permiten archivos de tipo: jpg, jpeg, png, gif, mp4, mov, avi.',
            ]
        );
        
        // Usar los datos validados para la creación
        $provinciaData = $validatedData;
        $provinciaData['geometria'] = !empty($validatedData['geometria']) ? json_decode($validatedData['geometria'], true) : null;
        $provinciaData['tipo_geometria'] = !empty($validatedData['geometria']) ? 'geojson' : null;

        try {
            $provincia = Provincia::create($provinciaData);
        } catch (\Illuminate\Database\QueryException $e) {

            if ($e->getCode() == '23505') {
                return redirect()->back()
                    ->withInput() // Mantiene los datos del formulario que el usuario ya llenó.
                    ->with('error', 'Error: Ha ocurrido un conflicto de ID en la base de datos. Esto puede deberse a una desincronización. Por favor, intente guardar de nuevo. Si el problema persiste, contacte al administrador.');
            }

            throw $e;
        }


        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $archivo) {
                $path = $archivo->store('provincias', 'public');
                $provincia->media()->create([
                    'archivo' => $path,
                    'tipo' => $archivo->getClientMimeType()
                ]);
            }
        }

        return redirect()->route('limites.provincias.index')->with('success', 'Provincia creada correctamente.');
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
    public function edit(Provincia $provincia)
    {
        $departamentos = Departamento::select('id', 'nombre')->get();
        
        if (is_string($provincia->geometria)) {
            $provincia->geometria = json_decode($provincia->geometria, true);
        }

        return view('limites.provincias.edit', compact('provincia', 'departamentos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     public function update(Request $request, Provincia $provincia)
    {
        $validatedData = $request->validate(
            [
                'id_departamento' => 'required|exists:departamentos,id',
                'nombre'          => 'required|string|max:255',
                'descripcion'     => 'nullable|string',
                'geometria'       => 'nullable|json',
                'media.*'         => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov,avi',
            ],
            [
                'id_departamento.required' => 'Debe seleccionar un departamento para la provincia.',
                'nombre.required'          => 'El campo nombre es obligatorio.',
                'geometria.json'           => 'El formato de la geometría no es válido.',
                'media.*.mimes'            => 'Solo se permiten archivos de tipo: jpg, jpeg, png, gif, mp4, mov, avi.',
            ]
        );

        $geometria = !empty($validatedData['geometria']) ? json_decode($validatedData['geometria'], true) : null;
        $tipo_geometria = !empty($validatedData['geometria']) ? 'geojson' : null;

        $provincia->update([
            'id_departamento' => $validatedData['id_departamento'],
            'nombre'          => $validatedData['nombre'],
            'descripcion'     => $validatedData['descripcion'] ?? null,
            'tipo_geometria'  => $tipo_geometria,
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
                $path = $archivo->store('provincias', 'public');
                $provincia->media()->create([
                    'archivo' => $path,
                    'tipo' => $archivo->getClientMimeType()
                ]);
            }
        }

        return redirect()->route('limites.provincias.index')->with('success', 'Provincia actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provincia $provincia)
    {
        foreach ($provincia->media as $media) {
            $media->delete();
        }

        $provincia->delete();

        return redirect()->route('limites.provincias.index')->with('success', 'Provincia eliminada correctamente.');
    }
    
    public function destroyMedia(Media $media)
    {
        $media->delete();
        return back()->with('success', 'Medio eliminado correctamente.');
    }
}
