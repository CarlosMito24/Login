<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cita;
use App\Models\Mascota; // Necesario para getMascotasByUser
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Rule; // Necesario para Rule::exists en updateAdmin
use Illuminate\Http\JsonResponse;

class CitaController extends Controller
{
    // ---------------------------------------------
    // CLIENTE (Usuario Autenticado)
    // ---------------------------------------------
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

    // ---------------------------------------------
    // ADMINISTRADOR (Admin)
    // ---------------------------------------------

    public function indexAdmin()
    {
        // Obtiene todas las citas incluyendo la relación 'user'
        $citas = Cita::with(['mascota', 'servicio', 'estado', 'user'])->get();
        return response()->json($citas);
    }

    public function showAdmin($id)
    {
        // Obtiene la cita por ID para rellenar el formulario de edición
        $cita = Cita::findOrFail($id); 
        return response()->json($cita);
    }

    public function getCitasPendientesAdmin()
    {
        $citas = Cita::with(['mascota.user', 'servicio', 'estado']) 
                     ->where('estado_id', 1) 
                     ->get();

        return response()->json($citas);
    }

    public function getCitasCompletadasAdmin()
    {
        return Cita::with(['mascota.user', 'servicio', 'estado'])
                     ->where('estado_id', 2) 
                     ->get();
    }
    
    /**
     * Obtiene la lista de mascotas que pertenecen a un usuario específico.
     * Usado por el frontend en la edición de citas para limitar el select.
     */
    public function getMascotasByUser($userId): JsonResponse
    {
        $mascotas = Mascota::where('user_id', $userId)->get(['id', 'nombre']);

        if ($mascotas->isEmpty()) {
            // No devolvemos 404 si el usuario no tiene mascotas, devolvemos 200 con un array vacío.
            // Esto permite que el frontend maneje la falta de opciones.
            return response()->json([]);
        }

        return response()->json($mascotas);
    }

    public function updateAdmin(Request $request, $id)
    {
        $cita = Cita::findOrFail($id);

        // Obtenemos el user_id de la cita actual para validar la mascota
        $userId = $cita->user_id;

        $rules = [
            'fecha' => 'sometimes|date',
            'hora' => 'sometimes',
            'servicios_id' => 'sometimes|exists:servicios,id',
            'estado_id' => 'sometimes|exists:estado_citas,id',
            
            // REGLA CLAVE: La mascota ID debe pertenecer al usuario de esta cita ($userId)
            'mascota_id' => [
                'sometimes', 
                Rule::exists('mascotas', 'id') 
                    ->where(function ($query) use ($userId) {
                        $query->where('user_id', $userId);
                    }),
            ],
        ];

        $request->validate($rules);

        $cita->update($request->only(['fecha', 'hora', 'mascota_id', 'servicios_id', 'estado_id']));
        return response()->json(['message' => 'Cita actualizada correctamente', 'data' => $cita], 200);
    }

    public function cancelAdmin(Request $request, $id)
    {
        $request->validate([
            'estado_id' => 'required|exists:estado_citas,id'
        ]);
        $cita = Cita::findOrFail($id);

        $cita->estado_id = $request->estado_id;
        $cita->save();

        return response()->json([
            'message' => 'Cita cancelada correctamente por el administrador', 
            'data' => $cita
        ]);
    }

    public function completarAdmin(Request $request, $id)
    {
        $request->validate([
            'estado_id' => 'required|exists:estado_citas,id'
        ]);
        $cita = Cita::findOrFail($id);

        $cita->estado_id = $request->estado_id;
        $cita->save();

        return response()->json([
            'message' => 'Cita completada correctamente por el administrador', 
            'data' => $cita
        ]);
    }
}