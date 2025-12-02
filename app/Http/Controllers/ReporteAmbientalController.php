<?php

namespace App\Http\Controllers;

use App\Models\ReporteAmbiental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReporteAmbientalController extends Controller
{
    /**
     * Aplica middleware de permisos a los métodos de administración.
     */
    public function __construct()
    {
        // Asegúrate de crear estos permisos en tu sistema de roles (p. ej. en un seeder)
        $this->middleware('permission:ver-reportes', ['only' => ['adminIndex']]);
        $this->middleware('permission:aprobar-reportes', ['only' => ['approve']]);
        $this->middleware('permission:eliminar-reportes', ['only' => ['destroy']]);
    }

    /**
     * Muestra los reportes aprobados (API pública).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $reportes = ReporteAmbiental::where('aprobado', true)->latest()->get();
        return response()->json(['data' => $reportes]);
    }

    /**
     * Muestra el panel de administración de reportes.
     *
     * @return \Illuminate\View\View
     */
    public function adminIndex()
    {
        $reportesPendientes = ReporteAmbiental::where('aprobado', false)->latest()->paginate(10, ['*'], 'pendientes_page');
        $reportesAprobados =  ReporteAmbiental::where('aprobado', true)->latest()->paginate(10, ['*'], 'aprobados_page');

        return view('reportes_ambientales.index', compact('reportesPendientes', 'reportesAprobados'));
    }

    /**
     * Almacena un nuevo reporte desde el formulario público.
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
            'contenido' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()->first()], 422);
        }

        ReporteAmbiental::create([
            'nombre' => $request->nombre,
            'titulo' => $request->titulo,
            'contenido' => $request->contenido,
            'aprobado' => false, 
        ]);

        return response()->json(['message' => '¡Gracias! Tu reporte ha sido enviado y será revisado por el administrador del sistema.'], 201);
    }

    /**
     * Aprueba un reporte pendiente.
     *
     * @param \App\Models\ReporteAmbiental $reporte
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approve(ReporteAmbiental $reporte)
    {
        $reporte->update(['aprobado' => true]);
        return back()->with('success', 'Reporte aprobado y publicado correctamente.');
    }

    /**
     * Elimina un reporte.
     *
     * @param \App\Models\ReporteAmbiental $reporte
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ReporteAmbiental $reporte)
    {
        $reporte->delete();
        return back()->with('success', 'Reporte eliminado correctamente.');
    }
}
