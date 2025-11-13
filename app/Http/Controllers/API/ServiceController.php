<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Servicios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ServiceController extends Controller
{
    private const DEFAULT_IMAGE_PATH = 'default/default.png';
    private const CUSTOM_IMAGES_DIR = 'servicios/';

    public function index()
    {
        return Servicios::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'precio' => 'nullable|numeric',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = self::DEFAULT_IMAGE_PATH; 

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName(); 
            $file->move(public_path('images/' . self::CUSTOM_IMAGES_DIR), $filename); 
            $imagePath = self::CUSTOM_IMAGES_DIR . $filename;
        }

        $servicios = Servicios::create([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'imagen' => $imagePath, 
        ]);
        
        return response()->json($servicios, 201);
    }

    public function show($id)
    {
        return Servicios::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $servicios = Servicios::findOrFail($id);

        $request->validate([
            'nombre' => 'sometimes|required|string|max:255',
            'descripcion' => 'sometimes|nullable|string',
            'precio' => 'sometimes|nullable|numeric',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['nombre', 'precio', 'descripcion']);

        if ($request->hasFile('imagen')) {
            $this->deleteOldImage($servicios->imagen);
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/' . self::CUSTOM_IMAGES_DIR), $filename);
            $data['imagen'] = self::CUSTOM_IMAGES_DIR . $filename;
        } 
        
        $servicios->update($data);

        return response()->json([
            'message' => 'Servicio actualizado correctamente',
            'servicio' => $servicios
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
        $servicios = Servicios::findOrFail($id);
        $this->deleteOldImage($servicios->imagen);
        $servicios->delete();

        return response()->json(['message' => 'Servicio eliminado correctamente']);
    }
}
