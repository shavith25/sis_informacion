<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::withCount('zonas')->paginate(10);
        return view('areas.index', compact('areas'));
    }

    public function create()
    {
        return view('areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'area' => 'required|regex:/^[\pL\s]+$/u',
            'descripcion' => 'nullable|string',
        ], [
            'area.required' => 'El campo área es obligatorio.',
            'area.regex' => 'El área solo puede contener letras y espacios.',
        ]);

        Area::create([
            'area' => $request->area,
            'descripcion' => $request->descripcion,
            'estado' => 1,
        ]);

        return redirect()->route('areas.index')->with('success', 'Área Protegida creada exitosamente.');
    }

    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'area' => 'required|regex:/^[\pL\s]+$/u',
            'descripcion' => 'nullable|string',
        ], [
            'area.required' => 'El campo área es obligatorio.',
            'area.regex' => 'El área solo puede contener letras y espacios.',
            'descripcion.regex' => 'La descripción solo puede contener letras, números, comas, puntos y #.',
        ]);

        $area->update([
            'area' => $request->area,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('areas.index')->with('success', 'Área Protegida actualizada exitosamente.');
    }

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

        return redirect()->route('areas.index')->with('success', 'Área eliminada correctamente.');
    }

    public function getAreas()
    {
        $areas = Area::orderBy('created_at', 'desc')->get();
        return response()->json($areas);
    }
}