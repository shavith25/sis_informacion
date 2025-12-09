<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especie;
use App\Models\EspecieImagen;
use App\Models\Zonas;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use Illuminate\Support\Str;

class EspecieController extends Controller
{
    public function index()
    {
        $especies = Especie::with('imagenes')->paginate(10); 
        return view('especies.index', compact('especies'));
    }

    public function create()
    {
        $zonas = Zonas::all();
        return view('especies.create', compact('zonas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'zona_id'     => 'required|exists:zonas,id',
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo'        => 'required|in:emblematica,vulnerable',
            'imagenes.*'  => 'image|mimes:jpeg,png,jpg,gif|max:20480',
            'documentos.*' => 'nullable|mimes:pdf,doc,docx|max:51200',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'tipo', 'zona_id']);
        $data['slug'] = Str::slug($request->titulo) . '-' . uniqid(); 

        $especie = Especie::create($data);

        // Guardar Imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('especies', 'public');
                EspecieImagen::create([
                    'especie_id' => $especie->id,
                    'url' => $path,
                ]);
            }
        }

        // Guardar Documentos
        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $archivo) {
                $ruta = $archivo->store('documentos', 'public');
                $tipo = str_contains($archivo->getMimeType(), 'pdf') ? 'pdf' : 'word';

                $especie->media()->create([
                    'tipo' => $tipo,
                    'archivo' => $ruta
                ]);
            }
        }

        return redirect()->route('especies.index')
                        ->with('success', 'Especie creada correctamente.');
    }

    public function show(Especie $especie)
    {
        $especie->load('imagenes');
        return view('especies.show', compact('especie'));
    }

    public function edit(Especie $especie)
    {
        $especie->load('imagenes');
        $zonas = Zonas::all();
        return view('especies.edit', compact('especie', 'zonas'));
    }

    public function update(Request $request, Especie $especie)
    {
        $request->validate([
            'zona_id'     => 'nullable|exists:zonas,id',
            'titulo'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo'        => 'required|in:emblematica,vulnerable',
            'imagenes.*'  => 'image|mimes:jpeg,png,jpg,gif|max:20480',
            'documentos.*' => 'nullable|mimes:pdf,doc,docx|max:51200',
        ]);

        $data = $request->only(['titulo', 'descripcion', 'tipo', 'zona_id']);
        
        if($especie->titulo != $request->titulo){
            $data['slug'] = Str::slug($request->titulo) . '-' . $especie->id;
        }

        $especie->update($data);

        // Eliminar imágenes seleccionadas
        if ($request->has('eliminar_imagenes')) {
            foreach ($request->eliminar_imagenes as $imagen_id) {
                $imagen = EspecieImagen::find($imagen_id);
                // Validación extra de seguridad
                if ($imagen && $imagen->especie_id == $especie->id) {
                    if(Storage::disk('public')->exists($imagen->url)){
                        Storage::disk('public')->delete($imagen->url);
                    }
                    $imagen->delete();
                }
            }
        }

        // Agregar nuevas imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('especies', 'public');
                EspecieImagen::create([
                    'especie_id' => $especie->id,
                    'url' => $path,
                ]);
            }
        }

        // Eliminar documentos seleccionados
        if ($request->has('eliminar_documentos')) {
            foreach ($request->eliminar_documentos as $doc_id) {
                $doc = $especie->media()->find($doc_id);
                if ($doc) {
                    if(Storage::disk('public')->exists($doc->archivo)){
                        Storage::disk('public')->delete($doc->archivo);
                    }
                    $doc->delete();
                }
            }
        }

        // Agregar nuevos documentos
        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $archivo) {
                $ruta = $archivo->store('documentos', 'public');
                $tipo = str_contains($archivo->getMimeType(), 'pdf') ? 'pdf' : 'word';
                $especie->media()->create([
                    'tipo' => $tipo,
                    'archivo' => $ruta
                ]);
            }
        }

        return redirect()->route('especies.index')
                        ->with('success', 'Especie actualizada correctamente.');
    }

    public function destroy(Especie $especie)
    {
        $especie->load(['imagenes', 'media']);

        // Eliminar Imágenes Físicas
        foreach ($especie->imagenes as $imagen) {
            if(Storage::disk('public')->exists($imagen->url)){
                Storage::disk('public')->delete($imagen->url);
            }
            $imagen->delete();
        }

        // Eliminar Documentos Físicos
        foreach ($especie->media as $doc) {
            if(Storage::disk('public')->exists($doc->archivo)){
                Storage::disk('public')->delete($doc->archivo);
            }
            $doc->delete();
        }

        $especie->delete();

        return redirect()->route('especies.index')
                        ->with('success', 'Especie eliminada y archivos limpiados correctamente.');
    }
}