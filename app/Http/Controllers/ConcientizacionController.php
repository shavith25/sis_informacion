<?php

namespace App\Http\Controllers;

use App\Models\ComentarioParticipacion as Comentario;
use App\Models\ComunidadArchivo;
use App\Models\Concientizacion;
use App\Models\ReporteAmbiental;
use App\Models\Sugerencia;
use Illuminate\Http\Request;

class ConcientizacionController extends Controller
{
    /**
     * Muestra todas las concientizaciones públicas (videos/material).
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $concientizaciones = Concientizacion::latest()->get();

        return view('concientizacion.index', compact('concientizaciones'));
    }

    /**
     * Devuelve los comentarios aprobados como JSON para la API.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicComentarios()
    {
        $comentarios = Comentario::where('aprobado', true)
            ->whereNull('parent_id')
            ->with('respuestasAprobadas')
            ->latest()
            ->get();
        return response()->json(['data' => $comentarios]);
    }

    /**
     * Devuelve las sugerencias aprobadas como JSON para la API pública.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicSugerencias()
    {
        $sugerencias = Sugerencia::where('aprobado', true)->latest()->get();
        return response()->json(['data' => $sugerencias]);
    }

    /**
     * Devuelve los reportes ambientales aprobados como JSON para la API pública.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicReportes()
    {
        $reportes = ReporteAmbiental::where('aprobado', true)->latest()->get();
        return response()->json(['data' => $reportes]);
    }

    /**
     * Devuelve los archivos (media) de la comunidad aprobados como JSON para la API pública.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPublicArchivos()
    {
        $archivos = ComunidadArchivo::where('aprobado', true)->latest()->get();
        return response()->json(['data' => $archivos]);
    }
}
