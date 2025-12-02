<?php

namespace App\Http\Controllers;

use App\Models\ComentarioParticipacion;
use Illuminate\Http\Request;

class ComentarioParticipacionController extends Controller
{
    /**
     * Muestra todos los comentarios con sus respuestas.
            ->whereNull('parent_id')
            ->latest()
            ->get();

        return response()->json($comentarios);
    }

    /**
     * Guarda un nuevo comentario o respuesta.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'comentario' => 'required|string',
            'parent_id' => 'nullable|exists:comentario_participaciones,id',
        ]);

        $comentario = ComentarioParticipacion::create($validated);

        return response()->json([
            'message' => 'Comentario guardado correctamente.',
            'data' => $comentario
        ], 201);
    }

    /**
     * Incrementa el contador de “me gusta”.
     */
    public function like($id)
    {
        $comentario = ComentarioParticipacion::findOrFail($id);
        $comentario->increment('likes');

        return response()->json([
            'message' => 'Like agregado correctamente.',
            'likes' => $comentario->likes
        ]);
    }

    /**
     * Elimina un comentario (y sus respuestas por cascada).
     */
    public function destroy($id)
    {
        $comentario = ComentarioParticipacion::findOrFail($id);
        $comentario->delete();

        return response()->json(['message' => 'Comentario eliminado correctamente.']);
    }
}
