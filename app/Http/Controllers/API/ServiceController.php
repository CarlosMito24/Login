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
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $file = $request->file('imagen');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/servicios'), $filename);
            $data['imagen'] = 'servicios/' . $filename; 
        } 
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
