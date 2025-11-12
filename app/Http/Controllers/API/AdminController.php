<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Cookie; 
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
        public function register(Request $request)
    {
        $rules = [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:9',
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|string|email|max:255|unique:admin',
            'password' => 'required|string|min:8',
        ];

        $messages = [
            'nombres.required' => 'El nombre es obligatorio.',
            'apellidos.required' => 'El apellido es obligatorio.',
            'telefono.required' => 'El teléfono es obligatorio.',
            'telefono.max' => 'El teléfono no debe superar los 9 dígitos.',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_nacimiento.date' => 'El formato de fecha de nacimiento es inválido.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El formato del email es inválido.',
            'email.unique' => 'Este email ya está registrado. Por favor, inicia sesión.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        ];

        try {
            $request->validate($rules, $messages);
        } catch (ValidationException $e) {
            throw $e; 
        }

        $admin = new Admin;
        $admin->nombres = $request->nombres;
        $admin->apellidos = $request->apellidos;
        $admin->telefono = $request->telefono;
        $admin->fecha_nacimiento = $request->fecha_nacimiento;
        $admin->email = $request->email;
        $admin->password = Hash::make($request->password);
        $admin->save();

        return response($admin, Response::HTTP_CREATED);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('email', $request->email)->first();

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $admin->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión correcto',
            'admin' => $admin,
            'token' => $token
        ]);
    }

     public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }

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
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
