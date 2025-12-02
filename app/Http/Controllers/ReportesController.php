<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Zonas;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportesController extends Controller
{
    /**
     * Muestra la vista del reporte con la cantidad de zonas y 
     * áreas registradas, así como la cantidad de zonas por área.
     */
    public function index()
    {
        $data = $this->getReportData();
        return view('reportes.index', $data);
    }

    /**
     * Genera un PDF con la información del reporte.
     */
    public function exportarPDF()
    {
        $data = $this->getReportData();
        $pdf = PDF::loadView('reportes.reporte_pdf', $data);
        return $pdf->download('reporte_zonas_areas.pdf');
    }

    /**
     * Obtiene los datos necesarios para los reportes.
     *
     * @return array
     */
    private function getReportData()
    {
        $countEventos = function ($tipo) {
            return Zonas::whereHas('eventos', function ($query) use ($tipo) {
                $query->where('tipo', $tipo);
            })->count();
        };

        return [
            'totalAreas' => Area::count(),
            'totalZonas' => Zonas::count(),
            'totalIncendios' => $countEventos('incendio'),
            'totalAvasallamientos' => $countEventos('avasallamiento'),
            'totalInundaciones' => $countEventos('inundacion'),
            'totalOtros' => $countEventos('otro'),
            'totalSequias' => $countEventos('sequia'),
            'totalLoteamientos' => $countEventos('loteamiento'),
            'totalBiodiversidad' => $countEventos('afectacion_biodiversidad'),
            'zonasPorArea' => Area::where('estado', true)
                ->withCount(['zonas' => fn($q) => $q->where('estado', true)])
                ->with(['zonas' => function ($query) {
                    $query->where('estado', true)
                        ->with(['historial' => fn($q) => $q->orderBy('created_at', 'desc')]);
                }])
                ->get()
        ];
    }
}


?>
