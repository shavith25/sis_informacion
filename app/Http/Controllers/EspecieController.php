<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Especie;
use App\Models\EspecieImagen;
use App\Models\Zonas;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;
use Illuminate\Support\Str; // [IMPORTANTE] Necesario para crear el slug

class EspecieController extends Controller
{
    public function index()
    {
        $especies = Especie::with('imagenes')->get();
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

        // [CORRECCIÓN] Generamos el slug automáticamente basado en el título
        $data = $request->only(['titulo', 'descripcion', 'tipo', 'zona_id']);
        // Ejemplo: "Oso Jucumari" -> "oso-jucumari-12345" (Uniqid evita duplicados)
        $data['slug'] = Str::slug($request->titulo) . '-' . uniqid(); 

        $especie = Especie::create($data);

        // Guardar Imágenes
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                // Esto guarda en storage/app/public/especies (Aislado de Zonas)
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
                
                // Usamos str_contains para mayor seguridad detectando mime types
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
        
        // [OPCIONAL] Actualizamos el slug si cambia el título
        if($especie->titulo != $request->titulo){
             $data['slug'] = Str::slug($request->titulo) . '-' . $especie->id;
        }

        $especie->update($data);

        // Eliminar imágenes seleccionadas
        if ($request->has('eliminar_imagenes')) {
            foreach ($request->eliminar_imagenes as $imagen_id) {
                $imagen = EspecieImagen::find($imagen_id);
                // Verificamos que la imagen pertenezca a ESTA especie por seguridad
                if ($imagen && $imagen->especie_id == $especie->id) {
                    Storage::disk('public')->delete($imagen->url);
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
                    Storage::disk('public')->delete($doc->archivo);
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

    // [ESTA ES LA PARTE IMPORTANTE PARA EL BORRADO SEGURO]
    public function destroy(Especie $especie)
    {
        // 1. Cargar relaciones
        $especie->load(['imagenes', 'media']);

        // 2. Eliminar Imágenes Físicas de la ESPECIE (No toca la zona)
        foreach ($especie->imagenes as $imagen) {
            // Verificamos existencia física antes de intentar borrar
            if(Storage::disk('public')->exists($imagen->url)){
                Storage::disk('public')->delete($imagen->url);
            }
            // Borramos registro de BD
            $imagen->delete();
        }

        // 3. Eliminar Documentos Físicos de la ESPECIE
        foreach ($especie->media as $doc) {
            if(Storage::disk('public')->exists($doc->archivo)){
                Storage::disk('public')->delete($doc->archivo);
            }
            $doc->delete();
        }

        // 4. Finalmente eliminamos la especie de la BD
        $especie->delete();

        return redirect()->route('especies.index')
                        ->with('success', 'Especie eliminada y archivos limpiados correctamente.');
    }
}