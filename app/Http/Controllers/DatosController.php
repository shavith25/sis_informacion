<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DatoImagen;
use App\Models\DatoMedio;
use App\Models\Datos;
use App\Models\Zonas;
use Illuminate\Support\Facades\Storage;

class DatosController extends Controller
{
public function store(Request $request)
    {
        $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'dato_id' => 'nullable|exists:datos,id',
            'flora_fauna' => 'required|string|max:512',
            'extension' => 'required|string|max:512',
            'poblacion' => 'required|string|max:512',
            'provincia' => 'required|string|max:512',
            'especies_peligro' => 'required|string|max:512',
            'otros_datos' => 'required|string|max:512',
            'imagenes.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8048',
            'videos.*' => 'nullable|mimes:mp4,avi,mov,mkv|max:51200',
            'documentos.*' => 'nullable|mimes:pdf,doc,docx|max:20480',
        ]);

        $dato = Datos::updateOrCreate(
            ['id' => $request->dato_id], 
            [
                'zona_id' => $request->zona_id,
                'flora_fauna' => $request->flora_fauna,
                'extension' => $request->extension,
                'poblacion' => $request->poblacion,
                'provincia' => $request->provincia,
                'especies_peligro' => $request->especies_peligro,
                'otros_datos' => $request->otros_datos,
            ]
        );

        $mensaje = $request->dato_id ? 'Datos actualizados correctamente.' : 'Datos registrados correctamente.';

        // 3. Manejo de Imágenes Nuevas
        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('datos_imagenes', 'public');
                DatoImagen::create([
                    'dato_id' => $dato->id,
                    'path' => $path,
                ]);
            }
        }

        // 4. Manejo de Videos Nuevos
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('datos_videos', 'public');
                DatoMedio::create([
                    'dato_id' => $dato->id,
                    'tipo' => 'video',
                    'path' => $path,
                ]);
            }
        }

        // 5. Manejo de Documentos Nuevos
        if ($request->hasFile('documentos')) {
            foreach ($request->file('documentos') as $doc) {
                $path = $doc->store('datos_documentos', 'public');
                DatoMedio::create([
                    'dato_id' => $dato->id,
                    'tipo' => 'documento',
                    'path' => $path,
                ]);
            }
        }

        // 6. Eliminar Imágenes Seleccionadas
        if ($request->filled('imagenes_eliminar')) {
            $imagenesEliminar = json_decode($request->imagenes_eliminar, true);
            if (is_array($imagenesEliminar)) {
                $imgs = DatoImagen::whereIn('id', $imagenesEliminar)->get();
                foreach ($imgs as $img) {
                    Storage::disk('public')->delete($img->path);
                    $img->delete();
                }
            }
        }

        // 7. Eliminar Medios (Videos/Docs) Seleccionados
        if ($request->filled('medios_eliminar')) {
            $mediosEliminar = json_decode($request->medios_eliminar, true);
            if (is_array($mediosEliminar)) {
                $medios = DatoMedio::whereIn('id', $mediosEliminar)->get();
                foreach ($medios as $medio) {
                    Storage::disk('public')->delete($medio->path);
                    $medio->delete();
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => $mensaje,
            'datos' => $dato->load('imagenes', 'medios')
        ]);
    }

    public function getDetalle($zonaId)
    {
        $datos = Datos::with(['imagenes', 'medios'])
                    ->where('zona_id', $zonaId)
                    ->latest() 
                    ->first(); 

        if ($datos) {
            return response()->json($datos);
        }

        return response()->json(['error' => 'No se encontraron datos para esta zona.'], 404);
    }
}
