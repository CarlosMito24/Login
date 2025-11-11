<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Cookie; 
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    // Registro
    public function register(Request $request)
    {
        $rules = [
            'nombres' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'telefono' => 'required|string|max:9',
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|string|email|max:255|unique:users',
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

        $user = new User;
        $user->nombres = $request->nombres;
        $user->apellidos = $request->apellidos;
        $user->telefono = $request->telefono;
        $user->fecha_nacimiento = $request->fecha_nacimiento;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response($user, Response::HTTP_CREATED);
    }

    // 🔹 Inicio de sesión
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión correcto',
            'user' => $user,
            'token' => $token
        ]);
    }
    
    // 🔹 Cerrar sesión
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Sesión cerrada correctamente']);
    }
}