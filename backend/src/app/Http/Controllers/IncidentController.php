<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\Log as AdminLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{

    public function index()
    {
        $incidents = Incident::with('user')->orderBy('created_at', 'desc')->get();
        
        return response()->json($incidents);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'asunto'      => 'required|string|max:255',
            'descripcion' => 'required|string',
        ]);

        $incident = Incident::create([
            'user_id'     => Auth::id(), 
            'subject'     => $validated['asunto'],
            'description' => $validated['descripcion'],
            'status'      => 'pendiente',
        ]);

        AdminLog::create([
            'clase' => 'Incidencia',
            'accion' => 'Crear',
            'descripcion' => "El usuario ha abierto una nueva incidencia: '{$incident->subject}' (ID: {$incident->id_incident}).",
            'user_id' => Auth::id(),
            'payload' => [
                'asunto' => $incident->subject
            ]
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Incidencia enviada correctamente',
            'data'    => $incident
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        $estadoAnterior = $incident->status;
        $incident->status = $request->status;
        $incident->save();

        AdminLog::create([
            'clase' => 'Incidencia',
            'accion' => 'Actualizar Estado',
            'descripcion' => "Estado de incidencia ID: {$id} modificado de '{$estadoAnterior}' a '{$request->status}'.",
            'user_id' => Auth::id(),
            'payload' => [
                'id_incidencia' => $id,
                'nuevo_estado' => $request->status
            ]
        ]);

        return response()->json($incident, 200);
    }
}