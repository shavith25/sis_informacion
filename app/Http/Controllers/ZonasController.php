<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Zonas;
use App\Models\ZonaMedio;
use App\Models\ZonaHistorial;
use App\Models\Especie;
use App\Models\Noticia;
use App\Models\Datos;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ZonasController extends Controller
{
    public function index()
    {
        $zonas = Zonas::with(['imagenes', 'videos', 'area'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(6);

        return view('zonas.index', compact('zonas'));
    }

    public function mapa()
    {
        $zonas = Zonas::with(['area', 'historial' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }, 'ultimoHistorial', 'imagenes', 'videos'])->where('estado', true)->get();

        return view('zonas.mapa', compact('zonas'));
    }

    public function detalle(Zonas $zona)
    {
        $zona->load(['ultimoHistorial', 'area', 'imagenes', 'videos', 'datos.medios']);
        $coordenadas = $zona->ultimoHistorial ? $zona->ultimoHistorial->coordenadas : [];

        return view('zonas.show', compact('zona', 'coordenadas'));
    }

    public function registradas()
    {
        $zonas = Zonas::with('area')->where('estado', true)->get();
        return response()->json($zonas);
    }
    public function listadoPublico()
    {
        $zonas = Zonas::with([
            'imagenes',
            'videos',
            'area',
            'historial' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }
        ])->get();
        $zonasActivas = $zonas->where('estado', true)->count();
        $datos = Datos::with('imagenes')->get();
        $especies = Especie::with('imagenes', 'media')->get();
        $noticias = Noticia::with('imagenes')->get();
        return view(
            'zonas.publico.zonas-protegidas',
            compact('zonas', 'zonasActivas', 'datos', 'especies', 'noticias')
        );
    }
    public function verInformacion(Zonas $zona)
    {
        $zona->load(['area', 'datos', 'ultimoHistorial', 'imagenes', 'videos']);

        $coordenadas = [];
        if ($zona->ultimoHistorial) {
            $coordenadas = $zona->ultimoHistorial->coordenadas;
        }

        return view('zonas.detalle', [
            'zona' => $zona,
            'coordenadas' => $coordenadas
        ]);
    }

    public function landing()
    {
        $areas = Area::with(['zonas' => function ($query) {
            $query->where('estado', true)->with('datos', 'imagenes', 'videos');
        }])->where('estado', true)->get();

        $zonasDestacadas = Zonas::where('estado', true)
            ->with(['area', 'datos', 'imagenes', 'videos'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('landing.zonas', compact('areas', 'zonasDestacadas'));
    }

    public function detalleZona(Zonas $zona)
    {
        $zona->load('area', 'datos', 'ultimoHistorial', 'imagenes', 'videos');
        $coordenadas = $zona->ultimoHistorial ? $zona->ultimoHistorial->coordenadas : null;

        return view('landing.detalle-zona', compact('zona', 'coordenadas'));
    }

    private function parseCoordenadas($coordenadas)
    {
        if (!is_array($coordenadas)) {
            return [];
        }

        $result = [];
        foreach ($coordenadas as $item) {
            if (!isset($item['tipo'])) continue;

            if ($item['tipo'] === 'marcador') {
                $result[] = [
                    'type' => 'marker',
                    'lat' => $item['coordenadas']['lat'],
                    'lng' => $item['coordenadas']['lng']
                ];
            } elseif ($item['tipo'] === 'poligono') {
                $points = [];
                foreach ($item['coordenadas'] as $coord) {
                    $points[] = [$coord['lat'], $coord['lng']];
                }
                $result[] = [
                    'type' => 'polygon',
                    'points' => $points
                ];
            }
        }

        return $result;
    }

    public function guardarDatos(Request $request, Zonas $zona)
    {
        $request->validate([
            'flora_fauna' => 'nullable|string',
            'extension' => 'nullable|string',
            'poblacion' => 'nullable|string',
            'provincia' => 'nullable|string|max:100',
            'especies_peligro' => 'nullable|string',
            'otros_datos' => 'nullable|string',
        ]);

        try {
            $datos = Datos::where('zona_id', $zona->id)->first();

            if ($datos) {
                $datos->update($request->all());
            } else {
                $datos = new Datos($request->all());
                $datos->zona_id = $zona->id;
                $datos->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Datos guardados correctamente',
                'zona_id' => $zona->id,
                'datos' => $datos
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar los datos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        $areas = Area::where('estado', 1)->get();
        return view('zonas.crear', compact('areas'));
    }

    public function changeStatus(Zonas $zona)
    {
        $zona->estado = !$zona->estado;
        $zona->save();

        return redirect()->route('zonas.index')->with('success', 'Estado de la zona modificado exitosamente');
    }

    public function show(Zonas $zona)
    {
        $zona->load(['ultimoHistorial', 'area', 'imagenes', 'videos']);

        $coordenadas = $zona->ultimoHistorial ? $zona->ultimoHistorial->coordenadas : [];

        return view('zonas.show', compact('zona', 'coordenadas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'coordenadas' => 'required|json',
            'area_id' => 'required|exists:areas,id',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480', // Maximo de 20MB
            'videos.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:50000',
            'imagen_mapa' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $coordenadas = json_decode($request->input('coordenadas'), true);

            if (!is_array($coordenadas)) {
                Log::error('Coordenadas no son un array válido', [
                    'input' => $request->input('coordenadas')
                ]);
                return back()->withErrors(['coordenadas' => 'Formato de coordenadas inválido.'])->withInput();
            }

            $tipoCoordenada = $this->determinarTipoCoordenada($coordenadas);

            // Procesar imágenes
            $imagenesPaths = [];
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $imagen) {
                    if ($imagen->isValid()) {
                        $path = $imagen->store('zonas/imagenes', 'public');
                        $imagenesPaths[] = $path;
                    }
                }
            }

            // Procesar videos
            $videosPaths = [];
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    if ($video->isValid()) {
                        $path = $video->store('zonas/videos', 'public');
                        $videosPaths[] = $path;
                    }
                }
            }

            // Crear la zona (sin coordenadas)
            $zona = Zonas::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'area_id' => $request->area_id,
                'coordenadas' => $coordenadas,
                'estado' => true,
                'tipo_coordenada' => $tipoCoordenada,
            ]);

            // Crear el primer registro en el historial
            ZonaHistorial::create([
                'zona_id' => $zona->id,
                'coordenadas' => $coordenadas,
                'tipo_coordenada' => $tipoCoordenada,
                'imagen_mapa' => $request->input('imagen_mapa'),
            ]);

            // ------------------- Manejo de nuevas imágenes en zona_medios -------------------
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $imageFile) {
                    if ($imageFile->isValid()) {
                        $path = $imageFile->store('zonas/imagenes', 'public');
                        ZonaMedio::create([
                            'zona_id' => $zona->id,
                            'tipo' => 'imagen',
                            'url' => $path,
                        ]);
                    }
                }
            }

            // ------------------- Manejo de nuevos videos en zona_medios -------------------
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $videoFile) {
                    if ($videoFile->isValid()) {
                        $path = $videoFile->store('zonas/videos', 'public');
                        ZonaMedio::create([
                            'zona_id' => $zona->id,
                            'tipo' => 'video',
                            'url' => $path,
                        ]);
                    }
                }
            }
            DB::commit();

            return redirect()->route('zonas.index')
                ->with('success', 'Zona creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear zona: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'coordenadas_input' => $request->input('coordenadas'),
                'has_imagenes' => $request->hasFile('imagenes'),
                'has_videos' => $request->hasFile('videos'),
            ]);

            if (app()->environment('local')) {
                throw $e; 
            }

            return back()->withInput()->withErrors(['error' => 'Error al crear la zona.']);


            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Error al crear la zona. Por favor, intente de nuevo.']);
        }
    }

    private function determinarTipoCoordenada($coordenadas)
    {
        $tipos = [];

        foreach ($coordenadas as $item) {
            if (isset($item['tipo'])) {
                $tipos[] = $item['tipo'];
            } elseif (isset($item['coordenadas'])) {
                $coord = $item['coordenadas'];

                if (isset($coord['lat']) && isset($coord['lng'])) {
                    $tipos[] = 'marcador';
                } elseif (is_array($coord) && count($coord) > 2 && isset($coord[0]['lat']) && isset($coord[0]['lng'])) {
                    $tipos[] = 'poligono';
                }
            }
        }

        if (in_array('poligono', $tipos)) {
            return in_array('marcador', $tipos) ? 'mixto' : 'poligono';
        }

        return 'marcador';
    }

    public function edit(Zonas $zona)
    {
        $zona->load([
            'historial',
            'imagenes',
            'videos'
        ]);

        $areas = Area::where('estado', 1)->get();

        return view('zonas.editar', compact('zona', 'areas'));
    }

    public function getAllDatos()
    {
        $datos = Datos::with(['zona.area', 'imagenes', 'medios'])->get();

        return response()->json($datos);
    }

    public function getDatoById($id)
    {
        $dato = Datos::with(['zona.area', 'imagenes', 'medios'])->find($id);

        if (!$dato) {
            return response()->json(['error' => 'Dato no encontrado'], 404);
        }

        return response()->json($dato);
    }

    public function update(Request $request, Zonas $zona)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'coordenadas' => 'required|json',
            'area_id' => 'required|exists:areas,id',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:20480',
            'videos.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:50000',
            'imagen_mapa' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $coordenadas = json_decode($request->input('coordenadas'), true);
            $tipoCoordenada = $this->determinarTipoCoordenada($coordenadas);

            // Crear Historial
            ZonaHistorial::create([
                'zona_id' => $zona->id,
                'coordenadas' => $coordenadas, 
                'tipo_coordenada' => $tipoCoordenada,
                'imagen_mapa' => $request->input('imagen_mapa'),
                'created_at' => now()
            ]);

            // Actualizar datos básicos
            $zona->nombre = $request->nombre;
            $zona->descripcion = $request->descripcion;
            $zona->area_id = $request->area_id;

            // Manejo de eliminación de imágenes
            if ($request->has('imagenes_eliminadas')) {
                foreach ($request->input('imagenes_eliminadas') as $ruta) {
                    ZonaMedio::where('zona_id', $zona->id)
                        ->where('tipo', 'imagen')
                        ->where('url', $ruta)
                        ->delete();
                    Storage::delete('public/' . $ruta);
                }
            }

            // Manejo de nuevas imágenes
            if ($request->hasFile('imagenes')) {
                foreach ($request->file('imagenes') as $imagen) {
                    $path = $imagen->store('zonas/imagenes', 'public');
                    ZonaMedio::create([
                        'zona_id' => $zona->id,
                        'tipo'    => 'imagen',
                        'url'     => $path,
                    ]);
                }
            }

            // Manejo de eliminación de videos
            if ($request->has('videos_eliminadas')) {
                foreach ($request->input('videos_eliminadas') as $ruta) {
                    ZonaMedio::where('zona_id', $zona->id)
                        ->where('tipo', 'video')
                        ->where('url', $ruta)
                        ->delete();
                    Storage::delete('public/' . $ruta);
                }
            }

            // Manejo de nuevos videos
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $path = $video->store('zonas/videos', 'public');
                    ZonaMedio::create([
                        'zona_id' => $zona->id,
                        'tipo'    => 'video',
                        'url'     => $path,
                    ]);
                }
            }

            $zona->save();
            DB::commit();

            return redirect()->route('zonas.index')
                ->with('success', 'Zona actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar zona: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la zona: ' . $e->getMessage());
        }
    }


    public function generarMapaPDF(Request $request, $id)
    {
        $zona = Zonas::findOrFail($id);

        $image = $request->file('image');
        $imagePath = 'public/mapa_zona_' . $zona->id . '.png';

        Storage::put($imagePath, file_get_contents($image));

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $pdf = new Dompdf($options);

        // Generar la vista para el PDF
        $html = view('reportes.mapa_pdf', [
            'zona' => $zona,
            'mapImageUrl' => Storage::url($imagePath)
        ])->render();

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->stream('mapa_zona_' . $zona->nombre . '.pdf');
    }

    public function destroy(Zonas $zona)
    {
        try {
            $zona->delete();

            return response()->json([
                'success' => true,
                'message' => 'Zona eliminada correctamente.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'No se pudo eliminar la zona.'
            ], 500);
        }
    }

    public function destroyDato($id)
    {
        $dato = Datos::find($id);

        if (!$dato) {
            return response()->json(['success' => false, 'message' => 'Dato no encontrado']);
        }

        // Eliminar las imágenes y medios relacionados
        $dato->imagenes()->delete();
        $dato->medios()->delete();

        $dato->delete();

        return response()->json(['success' => true, 'message' => 'Registro eliminado correctamente.']);
    }
}
