<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MascotaController extends Controller
{
    public function index()
    {
        return Mascota::where('user_id', Auth::id())->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'nullable|string',
            'raza' => 'nullable|string',
            'edad' => 'nullable|integer',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('imagen');
        $imagePath = null;

         if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/mascotas'), $filename);
            $imagePath = 'mascotas/' . $filename; 
        } 

        $mascota = Mascota::create([
            'user_id' => Auth::id(),
            'nombre' => $request->nombre,
            'especie' => $request->especie,
            'raza' => $request->raza,
            'edad' => $request->edad,
            'imagen' => $imagePath,
        ]);

        return response()->json($mascota, 201);
    }

    public function show($id)
    {
        $mascota = Mascota::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($mascota);
    }

    public function update(Request $request, $id)
    {
        $mascota = Mascota::where('user_id', Auth::id())->findOrFail($id);
    
    // 1. Validar los datos de entrada
    $request->validate([
        'nombre' => 'required|string|max:255',
        // ... otras validaciones
        'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
    ]);
    
    $data = $request->only(['nombre', 'especie', 'raza', 'edad']);

    // 2. Manejo de la nueva imagen
    if ($request->hasFile('imagen')) {
        // Eliminar la imagen antigua si existe
        if ($mascota->imagen && file_exists(public_path('images/' . $mascota->imagen))) {
            unlink(public_path('images/' . $mascota->imagen));
        }

        $file = $request->file('imagen');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('images/mascotas'), $filename);
        $data['imagen'] = 'mascotas/' . $filename;
    } 
    
    // 3. Actualizar la mascota
    $mascota->update($data);
    return response()->json($mascota);
    }

    public function destroy($id)
    {
        $mascota = Mascota::where('user_id', Auth::id())->findOrFail($id);

    // AGREGAR: Eliminar la imagen del servidor si existe
    if ($mascota->imagen && file_exists(public_path('images/' . $mascota->imagen))) {
        unlink(public_path('images/' . $mascota->imagen));
    }
    
    $mascota->delete();

    return response()->json(['message' => 'Mascota eliminada correctamente']);
    }
}
