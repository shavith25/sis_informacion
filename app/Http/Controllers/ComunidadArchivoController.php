<?php

namespace App\Http\Controllers;

use App\Models\ComunidadArchivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ComunidadArchivoController extends Controller
{
    /**
     * Aplica middleware de permisos a los métodos de administración.
     */
    public function __construct()
    {
        // Asegúrate de crear estos permisos en tu sistema (p. ej., con un seeder)
        $this->middleware('permission:ver-media', ['only' => ['adminIndex']]);
        $this->middleware('permission:aprobar-media', ['only' => ['approve']]);
        $this->middleware('permission:eliminar-media', ['only' => ['destroy']]);
    }

    /**
     * Almacena un nuevo archivo subido por la comunidad.
     * Por defecto, se guarda como no aprobado.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:100',
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'archivo' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov|max:51200',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('archivo');
        $path = $file->store('comunidad_archivos', 'public');

        $archivoComunidad = ComunidadArchivo::create([
            'nombre' => $request->nombre,
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'ruta_archivo' => $path,
            'mime_type' => $file->getClientMimeType(),
            'aprobado' => false, 
        ]);

        return response()->json([
            'message' => '¡Gracias! Tu archivo ha sido enviado y será revisado por el administrador.'
        ], 201);
    }

    /**
     * Muestra el panel de administración para gestionar imágenes y videos.
     *
     * @return \Illuminate\View\View
     */
    public function adminIndex()
    {
        // Archivos pendientes
        $pendientes = ComunidadArchivo::where('aprobado', false)->latest()->get();
        $videosPendientes = $pendientes->filter(fn($item) => str_starts_with($item->mime_type, 'video'));
        $imagenesPendientes = $pendientes->filter(fn($item) => str_starts_with($item->mime_type, 'image'));

        // Archivos aprobados
        $aprobados = ComunidadArchivo::where('aprobado', true)->latest()->get();
        $videosAprobados = $aprobados->filter(fn($item) => str_starts_with($item->mime_type, 'video'));
        $imagenesAprobadas = $aprobados->filter(fn($item) => str_starts_with($item->mime_type, 'image'));

        return view('comunidad_archivos.index', compact(
            'videosPendientes',
            'imagenesPendientes',
            'videosAprobados',
            'imagenesAprobadas'
        ));
    }

    /**
     * Aprueba un archivo pendiente.
     *
     * @param \App\Models\ComunidadArchivo $archivo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(ComunidadArchivo $archivo)
    {
        $archivo->update(['aprobado' => true]);
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'El archivo ha sido aprobado y publicado correctamente en la página web.']);
        }

        // Para solicitudes web estándar, redirige de vuelta a la página de índice.
        return Redirect::route('admin.media.index')->with('success', 'El archivo ha sido aprobado y publicado correctamente en la página web.');
    }

    /**
     * Elimina un archivo de la comunidad.
     *
     * @param \App\Models\ComunidadArchivo $archivo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ComunidadArchivo $archivo)
    {
        if (Storage::disk('public')->exists($archivo->ruta_archivo)) {
            Storage::disk('public')->delete($archivo->ruta_archivo);
        }
        $archivo->delete();
        return back()->with('success', 'El archivo ha sido eliminado correctamente.');
    }
}
