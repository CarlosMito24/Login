<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; // Se necesita para el método update y destroy

class MascotaController extends Controller
{
    // ... (Método index sin cambios)
    public function index()
    {
        return Mascota::where('user_id', Auth::id())->get();
    }

    /**
     * Define la imagen por defecto si no se sube ninguna.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'nullable|string',
            'raza' => 'nullable|string',
            'edad' => 'nullable|integer',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = 'mascotas/default.png'; // 👈 **IMAGEN POR DEFECTO**

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            // Nota: Es mejor usar un hash o nombre más seguro para el archivo.
            $filename = time() . '_' . $file->getClientOriginalName(); 
            // Guarda la imagen en public/images/mascotas
            $file->move(public_path('images/mascotas'), $filename); 
            $imagePath = 'mascotas/' . $filename;
        }

        $mascota = Mascota::create([
            'user_id' => Auth::id(),
            'nombre' => $request->nombre,
            'especie' => $request->especie,
            'raza' => $request->raza,
            'edad' => $request->edad,
            'imagen' => $imagePath, // Usa la ruta subida o la por defecto
        ]);

        return response()->json($mascota, 201);
    }

    // ... (Método show sin cambios)
    public function show($id)
    {
        $mascota = Mascota::where('user_id', Auth::id())->findOrFail($id);
        return response()->json($mascota);
    }


    /**
     * Maneja la actualización de la mascota y la imagen.
     */
  public function update(Request $request, $id)
    {
        // 1. Encontrar y verificar la propiedad de la mascota
        $mascota = Mascota::where('user_id', Auth::id())->findOrFail($id);

        // La validación 'sometimes' maneja automáticamente los casos PUT (todos los campos) y PATCH (algunos campos).
        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'especie' => 'sometimes|nullable|string',
            'raza' => 'sometimes|nullable|string',
            'edad' => 'sometimes|nullable|integer',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'eliminar_imagen' => 'nullable|boolean' 
        ]);

        // 2. Preparar los datos que se actualizarán (solo los campos que se enviaron)
        $data = $request->only(['nombre', 'especie', 'raza', 'edad']);

        // 3. Manejar subida de nueva imagen
        if ($request->hasFile('imagen')) {
            // Eliminar la imagen anterior SOLO si NO es la por defecto
            $this->deleteOldImage($mascota->imagen);

            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/mascotas'), $filename);
            $data['imagen'] = 'mascotas/' . $filename;
        } 
        
        // 4. Manejar solicitud de eliminación de imagen para volver a la por defecto
        elseif ($request->input('eliminar_imagen', false)) {
             // Eliminar la imagen anterior SOLO si NO es la por defecto
             $this->deleteOldImage($mascota->imagen);
             $data['imagen'] = 'mascotas/default.png'; // 👈 VOLVER A LA POR DEFECTO
        }
        
        // 5. Actualizar la mascota con los datos recopilados
        $mascota->update($data);

        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'mascota' => $mascota
        ]);
    }


    /**
     * Asegura que la imagen sea eliminada si existe y NO es la imagen por defecto.
     * Esto se hace para evitar eliminar el archivo 'default.png'.
     */
    protected function deleteOldImage($imagePath)
    {
        // Verificar si existe una imagen y si NO es la imagen por defecto
        if ($imagePath && $imagePath !== 'mascotas/default.png') {
            $fullPath = public_path('images/' . $imagePath);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }


    /**
     * Maneja la eliminación de la mascota y su imagen asociada.
     */
    public function destroy($id)
    {
        $mascota = Mascota::where('user_id', Auth::id())->findOrFail($id);

        // Eliminar la imagen del servidor si existe y NO es la por defecto
        $this->deleteOldImage($mascota->imagen);

        $mascota->delete();

        return response()->json(['message' => 'Mascota eliminada correctamente']);
    }
}