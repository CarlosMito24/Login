<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CitaController;
use App\Http\Controllers\API\EstadoCitaController;
use App\Http\Controllers\API\MascotaController;
use App\Http\Controllers\API\ServiceController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('user-profile', [AuthController::class, 'userProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/citas', [CitaController::class, 'index']);
    Route::get('citas/pendientes', [CitaController::class, 'getPendingAppointments']);
    Route::get('citas/historial', [CitaController::class, 'getHistorialCitas']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::put('/citas/{id}', [CitaController::class, 'update']);
    Route::patch('/citas/{id}/cancelar', [CitaController::class, 'cancel']);
    Route::delete('/citas/{id}', [CitaController::class, 'destroy']);
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
