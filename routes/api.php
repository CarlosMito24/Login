<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CitaController;
use App\Http\Controllers\API\EstadoCitaController;
use App\Http\Controllers\API\MascotaController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\UserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
   // 1. Rutas FIJAS (Sin {id} variable) - ¡Deben ir primero!
    Route::get('citas/pendientes', [CitaController::class, 'getPendingAppointments']);
    Route::get('citas/historial', [CitaController::class, 'getHistorialCitas']);
    
    // 2. Ruta PATCH personalizada (Cancelación) - Debe ir antes del resource si usa el mismo prefijo
    Route::patch('/citas/{id}/cancelar', [CitaController::class, 'cancel']); 

    // 3. RUTAS DE RECURSO - Define index, show, store, update, destroy automáticamente
    // Laravel ordena las rutas dentro del recurso correctamente.
    Route::resource('citas', CitaController::class)->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/mascotas', [MascotaController::class, 'index']);
    Route::post('/mascotas', [MascotaController::class, 'store']);
    Route::delete('/mascotas/{id}', [MascotaController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/servicios', [ServiceController::class, 'index']);
    Route::post('/servicios', [ServiceController::class, 'store']);
    Route::delete('/servicios/{id}', [ServiceController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/estados', [EstadoCitaController::class, 'index']);
    Route::post('/estados', [EstadoCitaController::class, 'store']);
    Route::delete('/estados/{id}', [EstadoCitaController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->group(function () {
    // 1. RUTA para OBTENER los datos (GET)
    Route::get('/user/profile', [UserController::class, 'show']);

    // 2. RUTA para ACTUALIZAR los datos (PUT/POST) - Es la que corregimos antes
    Route::put('/user/profile', [UserController::class, 'update']);
});