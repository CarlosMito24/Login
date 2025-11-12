<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

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

        $imagePath = 'mascotas/default.png'; 

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

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'especie' => 'sometimes|nullable|string',
            'raza' => 'sometimes|nullable|string',
            'edad' => 'sometimes|nullable|integer',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['nombre', 'especie', 'raza', 'edad']);

        if ($request->hasFile('imagen')) {
            
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/mascotas'), $filename);
            $data['imagen'] = 'mascotas/' . $filename;
        } 
        
        $mascota->update($data);

        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'mascota' => $mascota
        ]);
    }

    /**
     * Asegura que la imagen sea eliminada si existe y NO es la imagen por defecto.
     * Este método solo se usa en 'destroy' ahora.
     * @param string|null $imagePath
     */
    protected function deleteOldImage($imagePath): void
    {
        // Verificar si existe una imagen y si NO es la imagen por defecto
        if ($imagePath && $imagePath !== 'mascotas/default.png') {
            $fullPath = public_path('images/' . $imagePath);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }

    public function destroy($id)
    {
        $mascota = Mascota::where('user_id', Auth::id())->findOrFail($id);

        $this->deleteOldImage($mascota->imagen);

        $mascota->delete();

        return response()->json(['message' => 'Mascota eliminada correctamente']);
    }
}