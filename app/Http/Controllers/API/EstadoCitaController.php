<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EstadoCita;
use Illuminate\Http\Request;

class EstadoCitaController extends Controller
{
    public function index()
    {
        return EstadoCita::all();
    }

    public function show($id)
    {
        return EstadoCita::findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate(['nombre' => 'required|string|max:255']);
        $estado = EstadoCita::create($request->all());
        return response()->json($estado, 201);
    }

    public function update(Request $request, $id)
    {
        $estado = EstadoCita::findOrFail($id);
        $estado->update($request->all());
        return response()->json($estado);
    }

    public function destroy($id)
    {
        $estado = EstadoCita::findOrFail($id);
        $estado->delete();
        return response()->json(['message' => 'Estado eliminado correctamente']);
    }
}
