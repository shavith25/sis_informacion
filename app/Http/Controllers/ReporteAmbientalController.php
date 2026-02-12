<?php

namespace App\Http\Controllers;

use App\Models\ReporteAmbiental; // ✅ asegúrate que este modelo exista
use Illuminate\Http\Request;

class ReporteAmbientalController extends Controller
{
    public function __construct()
    {
        // ✅ permisos (ajusta los nombres a los que tengas en tu BD)
        $this->middleware('permission:ver-reportes-ambientales', ['only' => ['adminIndex']]);
        $this->middleware('permission:aprobar-reportes-ambientales', ['only' => ['approve']]);
        $this->middleware('permission:eliminar-reportes-ambientales', ['only' => ['destroy']]);
    }

    /**
     * ✅ API pública (si la necesitas): lista reportes aprobados
     * GET /public/reportes (solo lectura)
     */
    public function index()
    {
        $reportes = ReporteAmbiental::where('aprobado', true)->latest()->get();
        return response()->json(['data' => $reportes]);
    }

    /**
     * ✅ Panel ADMIN (tu ruta apunta aquí)
     * GET /admin/reportes-ambientales
     */
    public function adminIndex()
    {
        $reportesPendientes = ReporteAmbiental::where('aprobado', false)->latest()->get();
        $reportesAprobados  = ReporteAmbiental::where('aprobado', true)->latest()->get();

        return view('reportes_ambientales.index', compact('reportesPendientes', 'reportesAprobados'));
    }

    /**
     * ✅ Guardar reporte desde formulario público
     * POST /public/reportes
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'    => 'required|string|max:100',
            'titulo'    => 'required|string|max:255',
            'contenido' => 'required|string|max:2000',
            // si manejas tipo, ubicación, etc, agrégalos aquí
        ]);

        $reporte = ReporteAmbiental::create([
            'nombre'    => $request->nombre,
            'titulo'    => $request->titulo,
            'contenido' => $request->contenido,
            'aprobado'  => false,
        ]);

        return response()->json($reporte, 201);
    }

    /**
     * ✅ Aprobar reporte
     * PATCH /admin/reportes-ambientales/{reporte}/approve
     */
    public function approve(ReporteAmbiental $reporte)
    {
        $reporte->update(['aprobado' => true]);
        return back()->with('success', 'Reporte aprobado correctamente.');
    }

    /**
     * ✅ Eliminar reporte
     * DELETE /admin/reportes-ambientales/{reporte}
     */
    public function destroy(ReporteAmbiental $reporte)
    {
        $reporte->delete();
        return back()->with('success', 'Reporte eliminado correctamente.');
    }
}
