<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provincia;
use App\Models\Departamento;
use App\Models\Media;
use Illuminate\Support\Facades\Crypt;

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
        $departamentos = Departamento::all();
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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_departamento' => 'required|exists:departamentos,id',
            'descripcion' => 'nullable|string',
            'geometria' => 'required|json',
            'media.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:20480'
        ]);

        $provincia = Provincia::create([
            'nombre' => $request->nombre,
            'id_departamento' => $request->id_departamento,
            'descripcion' => $request->descripcion,
            'tipo_geometria' => 'geojson',
            'geometria' => json_decode($request->geometria, true)
        ]);
        
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $archivo) {
                $ruta = $archivo->store('provincias', 'public');
                $tipo = str_starts_with($archivo->getMimeType(), 'video') ? 'video' : 'imagen';
                $provincia->media()->create(['tipo' => $tipo, 'archivo' => $ruta]);
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
        $departamentos = Departamento::all();

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
        $request->validate([
            'nombre' => 'required|string|max:255',
            'id_departamento' => 'required|exists:departamentos,id',
            'descripcion' => 'nullable|string',
            'geometria' => 'required|json',
        ]);

        $provincia->update([
            'nombre' => $request->nombre,
            'id_departamento' => $request->id_departamento,
            'descripcion' => $request->descripcion,
            'tipo_geometria' => 'geojson',
            'geometria' => json_decode($request->geometria, true),
        ]);

        if ($request->has('delete_media')) {
            Media::destroy($request->delete_media);
        }
        
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $path = $file->store('provincias', 'public');
                $tipo = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';
                $provincia->media()->create(['archivo' => $path, 'tipo' => $tipo]);
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
        if ($provincia->media->count() > 0) {
            foreach ($provincia->media as $media) {
                $media->delete();
            }
        }
        $provincia->delete();
        return redirect()->route('limites.provincias.index')->with('success', 'Provincia eliminada correctamente.');
    }
}
