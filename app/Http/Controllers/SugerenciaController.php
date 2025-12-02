<?php

namespace App\Http\Controllers;

use App\Models\Sugerencia;
use Illuminate\Http\Request;

class SugerenciaController extends Controller
{
    /**
     * Aplica middleware de permisos a los métodos de administración.
     */
    public function __construct()
    {
        $this->middleware('permission:ver-sugerencias', ['only' => ['adminIndex']]);
        $this->middleware('permission:aprobar-sugerencias', ['only' => ['approve']]);
        $this->middleware('permission:eliminar-sugerencias', ['only' => ['destroy']]);
    }

    /**
     * Muestra las sugerencias aprobadas (API pública).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $sugerencias = Sugerencia::where('aprobado', true)->latest()->get();
        return response()->json(['data' => $sugerencias]);
    }

    /**
     * Muestra el panel de administración de sugerencias.
     * Requiere el permiso 'ver-sugerencias'.
     *
     * @return \Illuminate\View\View
     */
    public function adminIndex()
    {
        $sugerenciasPendientes = Sugerencia::where('aprobado', false)->latest()->get();
        $sugerenciasAprobadas = Sugerencia::where('aprobado', true)->latest()->get();

        return view('sugerencias.index', compact('sugerenciasPendientes', 'sugerenciasAprobadas'));
    }

    /**
     * Muestra las sugerencias aprobadas en la página pública (vista Blade).
     * Nota: Este método se usa si decides no cargar las sugerencias por AJAX.
     *
     * @return \Illuminate\View\View
     */
    public function publicIndex()
    {
        $sugerencias = Sugerencia::where('aprobado', true)->latest()->get();
        return view('sugerencias.publicas', compact('sugerencias'));
    }

    /**
     * Almacena una nueva sugerencia desde el formulario público.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string|max:1000',
        ]);

        $sugerencia = Sugerencia::create([
            'nombre' => $request->nombre,
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'aprobado' => false, 
        ]);

        return response()->json($sugerencia, 201);
    }

    /**
     * Aprueba una sugerencia pendiente.
     * Requiere el permiso 'aprobar-sugerencias'.
     *
     * @param \App\Models\Sugerencia $sugerencia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(Sugerencia $sugerencia)
    {
        $sugerencia->update(['aprobado' => true]);

        // Recarga la página de administración para reflejar el cambio
        return back()->with('success', 'Sugerencia aprobada correctamente.'); 
    }

    /**
     * Elimina una sugerencia.
     * Requiere el permiso 'eliminar-sugerencias'.
     *
     * @param \App\Models\Sugerencia $sugerencia
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Sugerencia $sugerencia)
    {
        $sugerencia->delete();

        return back()->with('success', 'Sugerencia eliminada correctamente.');
    }
}