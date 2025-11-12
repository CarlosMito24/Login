<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Mascota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class MascotaController extends Controller
{
    private const DEFAULT_IMAGE_PATH = 'default/default.png';
    private const CUSTOM_IMAGES_DIR = 'mascotas/';


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

        $imagePath = self::DEFAULT_IMAGE_PATH; 

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName(); 
            $file->move(public_path('images/' . self::CUSTOM_IMAGES_DIR), $filename); 
            $imagePath = self::CUSTOM_IMAGES_DIR . $filename;
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
            $this->deleteOldImage($mascota->imagen);
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/' . self::CUSTOM_IMAGES_DIR), $filename);
            $data['imagen'] = self::CUSTOM_IMAGES_DIR . $filename; // Nueva ruta
        } 
        
        $mascota->update($data);

        return response()->json([
            'message' => 'Mascota actualizada correctamente',
            'mascota' => $mascota
        ]);
    }

    /**
     * Asegura que la imagen sea eliminada si existe y NO es la imagen por defecto.
     * @param string|null $imagePath La ruta de la imagen en la base de datos (e.g., 'mascotas/foto.jpg' o 'default/default.png').
     */
    protected function deleteOldImage($imagePath): void
    {
        if ($imagePath && $imagePath !== self::DEFAULT_IMAGE_PATH) {
            $fullPath = public_path('images/' . $imagePath);
            if (str_starts_with($imagePath, self::CUSTOM_IMAGES_DIR) && File::exists($fullPath)) {
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