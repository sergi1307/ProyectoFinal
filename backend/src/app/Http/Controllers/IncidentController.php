<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incident;
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

        return response()->json([
            'status'  => 'success',
            'message' => 'Incidencia enviada correctamente',
            'data'    => $incident
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $incident = Incident::findOrFail($id);
        $incident->status = $request->status;
        $incident->save();

        return response()->json($incident, 200);
    }
}