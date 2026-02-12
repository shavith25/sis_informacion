<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Zonas;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesController extends Controller
{
    public function index()
    {
        $data = $this->getReportData();
        return view('reportes.index', $data);
    }

    public function exportarPDF()
    {
        $data = $this->getReportData();

        $pdf = Pdf::loadView('reportes.reporte_pdf', $data)
            ->setPaper('A4', 'portrait');

        return $pdf->download('reporte_zonas_areas.pdf');
    }

    private function getReportData()
    {
        // ✅ Cuenta ZONAS que tienen al menos 1 evento de tipo X
        $countZonasConEvento = function (string $tipo) {
            return Zonas::whereHas('eventos', function ($q) use ($tipo) {
                $q->where('tipo', $tipo);
            })->count();
        };

        // ✅ Si quieres contar EVENTOS (no zonas), usa esto en vez del de arriba:
        // $countEventos = fn(string $tipo) => \App\Models\ZonaEvento::where('tipo', $tipo)->count();

        $zonasPorArea = Area::where('estado', true)
            ->withCount(['zonas' => fn($q) => $q->where('estado', true)])
            ->with([
                'zonas' => function ($q) {
                    $q->where('estado', true)
                        // ✅ historial (último primero)
                        ->with(['historial' => fn($h) => $h->orderBy('created_at', 'desc')])
                        // ✅ eventos + medios (CLAVE para imágenes en reporte)
                        ->with([
                            'eventos' => function ($e) {
                                $e->orderBy('fecha_evento', 'desc')
                                ->orderBy('created_at', 'desc')
                                ->with('medios');
                            }
                        ]);
                }
            ])
            ->get();

        return [
            'totalAreas' => Area::count(),
            'totalZonas' => Zonas::count(),

            'totalIncendios' => $countZonasConEvento('incendio'),
            'totalAvasallamientos' => $countZonasConEvento('avasallamiento'),
            'totalInundaciones' => $countZonasConEvento('inundacion'),
            'totalOtros' => $countZonasConEvento('otro'),

            // ⚠️ Si tu BD enum no incluye estos tipos, estos contadores darán 0 siempre
            'totalSequias' => $countZonasConEvento('sequia'),
            'totalLoteamientos' => $countZonasConEvento('loteamiento'),
            'totalBiodiversidad' => $countZonasConEvento('afectacion_biodiversidad'),

            'zonasPorArea' => $zonasPorArea,
        ];
    }
}
