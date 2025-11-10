<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $user = Auth::user();
        
        if (!$user) {
            // Esto es una capa de seguridad, aunque el middleware ya lo debería manejar
            return response()->json(['message' => 'No autenticado'], 401);
        }

        // Devolvemos el usuario
        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['message' => 'No autenticado'], 401);
    }
    
    $request->validate([
        'nombres' => 'required|string|max:255',
        'apellidos' => 'required|string|max:255',
        'telefono' => 'required|string|max:9',
        'fecha_nacimiento' => 'required|date',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        // La contraseña debe ser opcional si no se desea cambiar
        'password' => 'nullable|string|min:8', // 'nullable' y 'confirmed' para verificar 
                                                          // que coincida si se proporciona
    ]);

    // 3. Preparar los datos para la actualización
    $data = $request->only(['nombres', 'apellidos', 'telefono', 'fecha_nacimiento', 'email']);

    // El password debe manejarse por separado y hashearse
    if ($request->filled('password')) {
        $data['password'] = bcrypt($request->input('password')); // O Hash::make()
    }

    // 4. Realizar la actualización
    $user->update($data);

    // 5. Devolver la respuesta
    return response()->json([
        'message' => 'Usuario actualizado correctamente', 
        'data' => $user // Devuelve los datos actualizados del usuario
    ]);
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
