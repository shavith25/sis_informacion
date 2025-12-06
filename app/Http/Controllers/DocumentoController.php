<?php

namespace App\Http\Controllers;

use App\Models\Documento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentoController extends Controller
{
    public function index()
    {
        $documentos = Documento::orderBy('fecha_publicacion', 'desc') 
                            ->orderBy('id', 'desc')            
                            ->paginate(4);                     
        return view('documentos.index', compact('documentos'));
    }

    public function create()
    {
        return view('documentos.create');
    }

    public function store(Request $request)
    {
    
        $request->validate([
            'titulo' => 'required|string|max:255',
            'resumen' => 'required|string',
            'fecha_publicacion' => 'required|date',
            'fecha_emision' => 'required|date',
            'numero_documento' => 'required|string|max:50',
            'icono' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pdf' => 'required|mimes:pdf|max:10000',
        ]);
        
        $data = $request->except(['icono', 'pdf']);

        if ($request->hasFile('icono')) {
            $path = $request->file('icono')->store('documentos/iconos', 'public');
            $data['icono'] = $path;
        }

        if ($request->hasFile('pdf')) {
            $data['pdf'] = $request->file('pdf')->store('documentos/pdf', 'public');
        }

        Documento::create($data);

        return redirect()->route('documentos.index')->with('toast_success', 'Documento creado exitosamente.');
    }

    public function edit(Documento $documento)
    {
        return view('documentos.edit', compact('documento'));
    }

    public function update(Request $request, Documento $documento)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'resumen' => 'required|string',
            'fecha_publicacion' => 'required|date',
            'fecha_emision' => 'required|date',
            'numero_documento' => 'required|string|max:50',
            'icono' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'pdf' => 'nullable|mimes:pdf|max:10000',
        ]);

        $data = $request->except(['icono', 'pdf']);

        if ($request->hasFile('icono')) {
            if ($documento->icono) {
                Storage::disk('public')->delete($documento->icono);
            }
            $data['icono'] = $request->file('icono')->store('documentos/iconos', 'public');
        }

        if ($request->hasFile('pdf')) {
            if ($documento->pdf) {
                Storage::disk('public')->delete($documento->pdf);
            }
            $data['pdf'] = $request->file('pdf')->store('documentos/pdf', 'public');
        }

        $documento->update($data);

        return redirect()->route('documentos.index')->with('toast_success', 'Documento actualizado exitosamente.');
    }

    public function destroy(Documento $documento)
    {
        if ($documento->icono) {
            Storage::disk('public')->delete($documento->icono);
        }
        if ($documento->pdf) {
            Storage::disk('public')->delete($documento->pdf);
        }
        $documento->delete();
        return redirect()->route('documentos.index')->with('toast_success', 'Documento eliminado exitosamente.');
    }
}
