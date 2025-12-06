<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Noticia;       
use App\Models\NoticiaImagen;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Error\Notice;

class NoticiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
       $noticias = Noticia::with('imagenes')->orderBy('fecha_publicacion', 'desc')->get();

        return view('noticias.index', compact('noticias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('noticias.create');
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
            'titulo' => 'required|string|max:255',
            'subtitulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'autor' => 'required|string|max:255',
            'fecha_publicacion' => 'required|date',
            'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif|max:8048',
        ]);

        $noticia = Noticia::create($request->only([
            'titulo', 'subtitulo', 'descripcion', 'autor', 'fecha_publicacion'
        ]));

        if ($request->hasFile('imagenes')) {
            foreach ($request->file('imagenes') as $imagen) {
                $path = $imagen->store('noticias', 'public');

                NoticiaImagen::create([
                    'noticia_id' => $noticia->id,
                    'ruta' => $path,
                ]);
            }
        }

        return redirect()->route('noticias.index')->with('success', 'Noticia creada correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Noticia $noticia)
    {
        $noticia->load('imagenes');

        return view('noticias.edit', compact('noticia'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, Noticia $noticia)
        {
            $request->validate([
                'titulo' => 'required|string|max:255',
                'subtitulo' => 'required|string|max:255',
                'descripcion' => 'required|string',
                'autor' => 'required|string|max:255',
                'fecha_publicacion' => 'required|date',
                'imagenes.*' => 'image|mimes:jpeg,png,jpg,gif|max:8048',
            ]);

            $noticia->update($request->only(['titulo', 'subtitulo', 'descripcion', 'autor', 'fecha_publicacion']));

            if ($request->has('eliminar_imagenes')) {
                foreach ($request->eliminar_imagenes as $imagen_id) {
                    $imagen = NoticiaImagen::find($imagen_id);
                    if ($imagen) {
                        Storage::disk('public')->delete($imagen->ruta);
                        $imagen->delete();
                    }
                }
            }

            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $imagen) {
                    $path = $imagen->store('noticias', 'public');
                    NoticiaImagen::create([
                        'noticia_id' => $noticia->id,
                        'ruta' => $path,
                    ]);
                }
            }

            return redirect()->route('noticias.index')->with('success', 'Noticia actualizada correctamente.');
        }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Noticia $noticia)
    {
        foreach($noticia->imagenes as $imagen){
            Storage::disk('public')->delete($imagen->ruta);
        }

        $noticia->delete();

        return redirect()->route('noticias.index')->with('success', 'Noticia eliminada correctamente.');
    }
}
