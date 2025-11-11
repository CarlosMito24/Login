<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CitaController extends Controller
{
    public function index()
    {
        return Cita::with(['mascota', 'servicio', 'estado'])->where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora' => 'required',
            'mascota_id' => 'required|exists:mascotas,id',
            'servicios_id' => 'required|exists:servicios,id',
            'estado_id' => 'required|exists:estado_citas,id'
        ]);

        $cita = Cita::create([
            'user_id' => Auth::id(),
            'fecha' => $request->fecha,
            'hora' => $request->hora,
            'mascota_id' => $request->mascota_id,
            'servicios_id' => $request->servicios_id,
            'estado_id' => $request->estado_id
        ]);

        return response()->json(['message' => 'Cita creada correctamente', 'data' => $cita], 201);
    }

    public function getPendingAppointments()
    {
        return Cita::with(['mascota', 'servicio', 'estado'])
                    ->where('user_id', Auth::id())
                    ->where('estado_id', 1) 
                    ->get();
    }

    public function getHistorialCitas()
    {
        return Cita::with(['mascota', 'servicio', 'estado'])
                    ->where('user_id', Auth::id())
                    ->where('estado_id', 2) 
                    ->get();
    }

    public function update(Request $request, $id)
    {
        $cita = Cita::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $request->validate([
            'fecha' => 'sometimes|date',
            'hora' => 'sometimes',
            'mascota_id' => 'sometimes|exists:mascotas,id',
            'servicios_id' => 'sometimes|exists:servicios,id',
            'estado_id' => 'sometimes|exists:estado_citas,id'
        ]);

        $cita->update($request->only(['fecha', 'hora', 'mascota_id', 'servicios_id', 'estado_id']));

        return response()->json(['message' => 'Cita actualizada correctamente', 'data' => $cita]);
    }

    public function cancel(Request $request, $id)
    {
        $cita = Cita::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        $request->validate([
            'estado_id' => 'required|exists:estado_citas,id'
        ]);
    
        $cita->estado_id = $request->estado_id;
        $cita->save();
    
        return response()->json(['message' => 'Cita cancelada correctamente', 'data' => $cita]);
    
    }

    public function destroy($id)
    {
        $cita = Cita::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cita->delete();

        return response()->json(['message' => 'Cita eliminada correctamente']);
    }

    public function show($id)
{
    return Cita::with(['mascota', 'servicio', 'estado'])
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail(); 
}
}
