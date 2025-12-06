<?php

namespace App\Http\Controllers;
use App\Models\Concientizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PanelConcientizacionController extends Controller
{
    /**
     * Muestra todas las concientizaciones.
     */
   public function index()
    {
        $concientizaciones = Concientizacion::latest()->paginate(10);
        return view('panelConcientizaciones.index', compact('concientizaciones'));
    }
    public function show($id)
    {
        //
    }

    public function create()
    {
        return view('panelConcientizaciones.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string',
            'video' => 'required|mimes:mp4,webm,ogg|max:51200',
        ]);

        $path = $request->file('video')->store('videos_concientizacion', 'public');

        Concientizacion::create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'video_path' => $path,
        ]);

        return redirect()->route('panelConcientizaciones.index')->with('toast_success', 'Video agregado correctamente.');
    }

    public function edit(Concientizacion $concientizacion)
    {
        $concientizacion = (new Concientizacion)->resolveRouteBinding($concientizacion->firstOrFail()->id);
        return view('panelConcientizaciones.edit', compact('concientizacion'));
    }

    public function update(Request $request, Concientizacion $concientizacion)
    {
        $concientizacion = (new Concientizacion)->resolveRouteBinding($concientizacion->firstOrFail()->id);

        $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'categoria' => 'required|string',
            'video' => 'nullable|mimes:mp4,webm,ogg|max:51200', 
        ]);

        $data = [
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'categoria' => $request->categoria,
            'video_path' => $concientizacion->video_path,
        ];

        // Si suben un video nuevo...
        if ($request->hasFile('video')) {
            if ($concientizacion->video_path && Storage::disk('public')->exists($concientizacion->video_path)) {
                Storage::disk('public')->delete($concientizacion->video_path);
            }
            
            $path = $request->file('video')->store('videos_concientizacion', 'public');
            $data['video_path'] = $path;
        }

        $concientizacion->update($data);

        return redirect()
            ->route('panelConcientizaciones.index')
            ->with('toast_success', 'Video actualizado correctamente.');
        
    }

    public function destroy($id)
    {
        $concientizacion = (new Concientizacion)->resolveRouteBinding($id);

        if($concientizacion->video_path && Storage::disk('public')->exists($concientizacion->video_path)) {
            Storage::disk('public')->delete($concientizacion->video_path);
        }

        $concientizacion->delete();

        return redirect()
            ->route('panelConcientizaciones.index')
            ->with('toast_success', 'Video eliminado correctamente.');
    }
}
