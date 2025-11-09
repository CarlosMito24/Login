<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Servicios;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        return Servicios::all();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'nullable|numeric',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validación de imagen
        ]);

        // 2. PREPARACIÓN DE DATOS: Capturamos todos los datos validados.
        $data = $request->except('imagen'); // Empezamos sin el archivo binario 'imagen'

        // 3. MANEJO Y GUARDADO DE IMAGEN
        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            // Usamos el helper de Laravel Storage para nombrar y guardar el archivo
            // Opcionalmente, puedes usar Storage::putFile('servicios', $file, 'public');
            
            // Creamos un nombre único
            $filename = time() . '_' . $file->getClientOriginalName();
            
            // Guardamos el archivo en la carpeta 'public/images/servicios/'
            $file->move(public_path('images/servicios'), $filename);
            
            // Almacenamos la RUTA que se guardará en la base de datos
            // Nota: Usamos 'images/servicios/' para coincidir con lo que usamos en el seeder.
            $data['imagen'] = 'servicios/' . $filename; 
        } 
        
        // 4. CREACIÓN DEL SERVICIO: Usamos la variable $data, que ahora contiene
        //    el nombre de archivo correcto si se subió una imagen.
        $servicios = Servicios::create($data);
        
        return response()->json($servicios, 201);
    }

    public function show($id)
    {
        return Servicios::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $servicios = Servicios::findOrFail($id);
        $servicios->update($request->all());
        return response()->json($servicios);
    }

    public function destroy($id)
    {
        $servicios = Servicios::findOrFail($id);
        $servicios->delete();

        return response()->json(['message' => 'Servicio eliminado correctamente']);
    }
}
