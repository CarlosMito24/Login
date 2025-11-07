<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller
{
    public function index()
    {
        return Cita::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'descripcion' => 'nullable|string',
        ]);

        $cita = Cita::create([
            'user_id' => Auth::id(),
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'descripcion' => $request->descripcion,
        ]);

        return response()->json($cita, 201);
    }

    public function destroy($id)
    {
        $cita = Cita::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cita->delete();

        return response()->json(['message' => 'Cita eliminada correctamente']);
    }
}
