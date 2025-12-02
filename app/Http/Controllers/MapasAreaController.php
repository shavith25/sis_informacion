<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Area;  
use App\Models\Zonas;
use App\Models\ZonaMedio;

use App\Models\ZonaEvento;
use App\Models\ZonaEventoMedio;
use App\Models\Municipio;

use App\Models\ZonaHistorial;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; 
use Illuminate\Validation\ValidationException; 
class MapasAreaController extends Controller{
    public function index()
    {
        return view('mapa-areas.index');
    }
    public function storeFromMapa(Request $request)
    {
        $request->validate([
            'area' => 'required|regex:/^[\pL\s]+$/u|max:30',
            'descripcion' => 'nullable|string|max:150|regex:/^[\pL\pN\s,\.#]+$/u',
        ]);

        Area::create([
            'area' => $request->area,
            'descripcion' => $request->descripcion,
            'estado' => 1,
        ]);

        return response()->json(['message' => 'Área Protegida registrada correctamente.'], 200);
    }
    public function storeZonaFromMapa(Request $request)
    {
        try {
            $request->validate([
                'zona_nombre' => 'required|string|max:255', 
                'descripcion_zona' => 'nullable|string',     
                'coordenadas' => 'required|json',
                'area_id' => 'required|exists:areas,id',
                'imagen_zona.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8048', 
                'video_zona.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
                'imagen_mapa' => 'nullable|string',      
            ]);

        } catch (ValidationException $e) {
        
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors(),
            ], 422); 
        }
        Log::debug('Archivos imagen_zona:', ['files' => $request->file('imagen_zona')]);
        Log::debug('Archivos video_zona:', ['files' => $request->file('video_zona')]);
        DB::beginTransaction();
        try {
            $coordenadas = json_decode($request->input('coordenadas'), true);

            $imagenesPaths = [];
            if ($request->hasFile('imagen_zona')) { 
                foreach ($request->file('imagen_zona') as $imagen) {
                    if ($imagen->isValid()) {
                        $path = $imagen->store('zonas/imagenes', 'public');
                        $imagenesPaths[] = $path;
                    }
                }
            }

            $videosPaths = [];
            if ($request->hasFile('video_zona')) { 
                foreach ($request->file('video_zona') as $video) {
                    if ($video->isValid()) {
                        $path = $video->store('zonas/videos', 'public');
                        $videosPaths[] = $path;
                    }
                }
            }

            $zona = Zonas::create([
                'nombre' => $request->zona_nombre,
                'descripcion' => $request->descripcion_zona, 
                'area_id' => $request->area_id,
                // 'imagenes' => $imagenesPaths,
                // 'videos' => $videosPaths,
                'coordenadas' => $coordenadas, 
                'estado' => true,
                'tipo_coordenada' => $request->tipo_coordenada,
            ]);

            
            ZonaHistorial::create([
                'zona_id' => $zona->id,
                'coordenadas' => $coordenadas,
                'tipo_coordenada' => $request->tipo_coordenada,
                'imagen_mapa' => $request->input('imagen_mapa'),
            ]);

            foreach ($imagenesPaths as $path) {
                ZonaMedio::create([
                    'zona_id' => $zona->id,
                    'tipo' => 'imagen',
                    'url' => $path,
                    'descripcion' => null, 
                ]);
            }

            foreach ($videosPaths as $path) {
                ZonaMedio::create([
                    'zona_id' => $zona->id,
                    'tipo' => 'video',
                    'url' => $path,
                    'descripcion' => null,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Zona registrada correctamente.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear zona: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear la zona. Por favor, intente de nuevo.'], 500);
        }
    }
    public function storeZonaIncidenciaFromMapa(Request $request)
    {
        try {
            $request->validate([
                'zona_nombre_incidencia' => 'required|string|max:255', 
                'descripcion_zona_incidencia' => 'nullable|string',     
                'coordenadas' => 'required|json',
                'tipo_incidencia' => 'nullable|string',  
                'zona_id' => 'required|exists:zonas,id',
                'imagen_zona_incidencia.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:8048', 
                'video_zona_incidencia.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
                'imagen_mapa' => 'nullable|string',  
                'fecha_incidencia' => 'nullable|date_format:Y-m-d', 
            ]);

        } catch (ValidationException $e) {
        
            return response()->json([
                'message' => 'Los datos proporcionados no son válidos.',
                'errors' => $e->errors(),
            ], 422); 
        }
        Log::debug('Archivos imagen_zona_incidencia:', ['files' => $request->file('imagen_zona_incidencia')]);
        Log::debug('Archivos video_zona_incidencia:', ['files' => $request->file('video_zona_incidencia')]);
        DB::beginTransaction();
        try {
            $coordenadas = json_decode($request->input('coordenadas'), true);

            $imagenesPaths = [];
            if ($request->hasFile('imagen_zona_incidencia')) { 
                foreach ($request->file('imagen_zona_incidencia') as $imagen) {
                    if ($imagen->isValid()) {
                        $path = $imagen->store('zonas/imagenes', 'public');
                        $imagenesPaths[] = $path;
                    }
                }
            }

            $videosPaths = [];
            if ($request->hasFile('video_zona_incidencia')) { 
                foreach ($request->file('video_zona_incidencia') as $video) {
                    if ($video->isValid()) {
                        $path = $video->store('zonas/videos', 'public');
                        $videosPaths[] = $path;
                    }
                }
            }

            $zonaIncidencia = ZonaEvento::create([
                'titulo' => $request->zona_nombre_incidencia,
                'descripcion' => $request->descripcion_zona_incidencia, 
                'tipo' => $request->tipo_incidencia,
                'zona_id' => $request->zona_id,
                'coordenadas' => $coordenadas, 
                'estado' => 'activo',
                'tipo_coordenada' => $request->tipo_coordenada,
                'fecha_evento' => $request->input('fecha_incidencia', now()),
            ]);

            
            // ZonaHistorial::create([
            //     'evento_id' => $zonaIncidencia->id,
            //     'coordenadas' => $coordenadas,
            //     'tipo_coordenada' => $request->tipo_coordenada,
            //     'imagen_mapa' => $request->input('imagen_mapa'),
            // ]);

            foreach ($imagenesPaths as $path) {
                ZonaEventoMedio::create([
                    'evento_id' => $zonaIncidencia->id,
                    'tipo' => 'imagen',
                    'url' => $path,
                    'descripcion' => null, 
                ]);
            }

            foreach ($videosPaths as $path) {
                ZonaEventoMedio::create([
                    'evento_id' => $zonaIncidencia->id,
                    'tipo' => 'video',
                    'url' => $path,
                    'descripcion' => null,
                ]);
            }

            DB::commit();

            return response()->json(['message' => 'Incidente registrado correctamente.'], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear Incidencia: ' . $e->getMessage());
            return response()->json(['message' => 'Error al crear la Incidencia. Por favor, intente de nuevo.'], 500);
        }
    }
    public function getZonas()
    {
        $zonas = Zonas::with(['eventos','ultimoHistorial'])
        ->where('estado', true)
        ->get([
            'id',
            'nombre',
            'descripcion',
            'tipo_coordenada'
        ]);

        return response()->json($zonas);
    }
    public function getMunicipiosByProvincia($provinciaId)
    {
        $municipios = Municipio::where('id_provincia', $provinciaId)
                            ->select('id', 'nombre','geometria','tipo_geometria')
                            ->get();
        return response()->json($municipios);
    }

    public function getMunicipioGeometria($municipioId)
    {
        $municipio = Municipio::find($municipioId);
        if (!$municipio || !$municipio->geometria) {
            return response()->json(['error' => 'Municipio no encontrado o sin geometría'], 404);
        }
        return response()->json([
            'id' => $municipio->id,
            'nombre' => $municipio->nombre,
            'tipo_geometria' => $municipio->tipo_geometria,
            'geometria' => $municipio->geometria 
        ]);
    }
}

