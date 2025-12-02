<?php

namespace App\Http\Controllers;

use App\Models\ComentarioParticipacion as Comentario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ComentarioController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver-comentarios', ['only' => ['index']]);
        $this->middleware('permission:aprobar-comentarios', ['only' => ['approve']]);
        $this->middleware('permission:eliminar-comentarios', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        if ($request->expectsJson()) {
            return $this->getAprobados();
        }

        $comentariosPendientes = Comentario::where('aprobado', false)->latest()->get();
        $comentariosAprobados = Comentario::where('aprobado', true)->latest()->get();

        return view('comentarios.index', compact('comentariosPendientes', 'comentariosAprobados'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|max:255',
            'comentario' => 'required|string',
            'parent_id' => 'nullable|exists:' . (new Comentario)->getTable() . ',id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $data = $validator->validated();
        $data['aprobado'] = false;

        Comentario::create($data);

        return response()->json(['success' => true, 'message' => '¡Gracias! Tu comentario ha sido enviado y será revisado.'], 201);
    }

    public function getAprobados()
    {
        try {
            $comentarios = Comentario::where('aprobado', true)
                ->whereNull('parent_id')
                ->with('respuestasAprobadas')
                ->latest()
                ->get();

            return response()->json(['data' => $comentarios]);
        } catch (Exception $e) {
            Log::error('Error fetching approved comments for API:', ['exception' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Error al cargar los comentarios debido a un fallo en el servidor.',
                'details' => config('app.debug') ? $e->getMessage() : 'Internal Server Error.',
                'data' => []
            ], 500);
        }
    }

    public function approve($id)
    {
        $comentario = Comentario::findOrFail($id);
        $comentario->update(['aprobado' => true]);

        return back()->with('success', 'Comentario aprobado correctamente.');
    }

    public function destroy($id)
    {
        $comentario = Comentario::findOrFail($id);
        $comentario->respuestas()->delete();
        $comentario->delete();

        return back()->with('success', 'Comentario eliminado correctamente.');
    }

    public function like($id)
    {
        $comentario = Comentario::find($id);

        if (!$comentario) {
            return response()->json(['message' => 'Comentario no encontrado.'], 404);
        }

        try {
            $comentario->increment('likes');

            return response()->json(['message' => 'Like agregado correctamente.', 'likes' => $comentario->likes]);
        } catch (Exception $e) {
            Log::error('Error al registrar "Me gusta" para el comentario ' . $id . ': ' . $e->getMessage());
            return response()->json(['message' => 'No se pudo registrar el "Me gusta".'], 500);
        }
    }
}

