<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::withCount('zonas')->paginate(10);
        return view('areas.index', compact('areas'));
    }
    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('areas.create');
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
            'area' => 'required|regex:/^[\pL\s]+$/u',
            'descripcion' => 'nullable|string',
        ], [
            'area.required' => 'El campo área es obligatorio.',
            'area.regex' => 'El área solo puede contener letras y espacios.',
            //'area.max' => 'El área no puede tener más de 50 caracteres.',
        ]);

        Area::create([
            'area' => $request->area,
            'descripcion' => $request->descripcion,
            'estado' => 1,
        ]);

        return redirect()->route('areas.index')->with('success', 'Área Protegida creada exitosamente.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'area' => 'required|regex:/^[\pL\s]+$/u',
            'descripcion' => 'nullable|string',
        ], [
            'area.required' => 'El campo área es obligatorio.',
            'area.regex' => 'El área solo puede contener letras y espacios.',
            //'area.max' => 'El área no puede tener más de 50 caracteres.',
            'descripcion.regex' => 'La descripción solo puede contener letras, números, comas, puntos y #.',
        ]);

        $area->update([
            'area' => $request->area,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('areas.index')->with('success', 'Área Protegida actualizada exitosamente.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Area  $area
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Area $area)
    // {
    //     // Cambiar el estado: 1 (activo) a 0 (inactivo), y viceversa
    //     $area->update(['estado' => $area->estado == 1 ? 0 : 1]);
    
    //     $message = $area->estado == 1 ? 'Área activada exitosamente.' : 'Área desactivada exitosamente.';
    //     return redirect()->route('areas.index')->with('success', $message);
    // }
    public function toggleEstado(Area $area)
    {
        $area->update(['estado' => $area->estado == 1 ? 0 : 1]);

        $message = $area->estado == 1
            ? 'Área activada exitosamente.'
            : 'Área desactivada exitosamente.';

        return redirect()->route('areas.index')->with('success', $message);
    }

    public function destroy(Area $area)
    {
        $area->delete();

        return redirect()->route('areas.index');
    }
    public function getAreas()
    {
        $areas = Area::orderBy('created_at', 'desc')->get();
        return response()->json($areas);
    }
    
}
