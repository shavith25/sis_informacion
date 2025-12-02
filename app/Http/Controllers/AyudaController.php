<?php

namespace App\Http\Controllers;

use App\Models\DocumentoAyuda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AyudaController extends Controller
{
    public function index()
    {
        $documentos = DocumentoAyuda::orderBy('created_at', 'desc')->get();
        return view('ayuda.index', compact('documentos'));
    }

    public function subir(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|mimes:pdf|max:15360', // max 15MB
        ], [ 
            'pdf_file.required' => 'Debe seleccionar un archivo PDF.',
            'pdf_file.mimes' => 'Solo se permiten archivos en formato PDF.',
            'pdf_file.max' => 'El tamaño máximo del archivo es 15MB.',
        ]);

        $file = $request->file('pdf_file');

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = time() . '_' . $originalName . '.pdf';

        $path = $file->storeAs('documentos_ayuda', $fileName, 'public');

        $documento = DocumentoAyuda::create([
            'titulo' => $originalName,
            'autor' => auth()->check() ? auth()->user()->name : 'Administrador', 
            'nombre_archivo' => $fileName,
            'ruta_archivo' => $path,
            'tamano_bytes' => $file->getSize(),
        ]);

        return response()->json([
            'message' => 'Documento subido con éxito.',
            'documento' => $documento,
            'ruta_publica' => Storage::url($path), 
        ], 201);
    }

    public function descargar($id)
    {
        $documento = DocumentoAyuda::findOrFail($id);

        if (!Storage::disk('public')->exists($documento->ruta_archivo)) {
            abort(404, 'El documento no fue encontrado.');
        }

        $documento->increment('descargas');

        $path = Storage::disk('public')->download($documento->ruta_archivo);

        return response()->download($path, $documento->titulo . '.pdf');
    }

    public function visualizar($id)
    {
        $documento = DocumentoAyuda::findOrFail($id);

        $url = Storage::url($documento->ruta_archivo);

        return redirect($url);
    }

    public function eliminar($id)
    {
        $documento = DocumentoAyuda::findOrFail($id);

        try {
            if (Storage::disk('public')->exists($documento->ruta_archivo)) {
                Storage::disk('public')->delete($documento->ruta_archivo);
            }

            $documento->delete();

            return response()->json([
                'message' => 'Documento "' . $documento->titulo . '" eliminado con éxito.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al eliminar el documento: ' . $e->getMessage()
            ], 500);
        }
    }
}
