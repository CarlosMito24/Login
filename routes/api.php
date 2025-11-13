<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CitaController;
use App\Http\Controllers\API\EstadoCitaController;
use App\Http\Controllers\API\MascotaController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\AdminController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('citas/pendientes', [CitaController::class, 'getPendingAppointments']);
    Route::get('citas/historial', [CitaController::class, 'getHistorialCitas']);
    Route::post('/citas', [CitaController::class, 'store']);
    Route::put('/citas/{id}', [CitaController::class, 'update']);
    Route::patch('/citas/{id}/cancelar', [CitaController::class, 'cancel']);
    Route::delete('/citas/{id}', [CitaController::class, 'destroy']);
    Route::resource('citas', CitaController::class)->only([
        'index', 'show', 'store', 'update', 'destroy'
    ]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/mascotas', [MascotaController::class, 'index']);
    Route::post('/mascotas', [MascotaController::class, 'store']);
    Route::get('/mascotas/{id}', [MascotaController::class, 'show']);
    Route::put('/mascotas/{id}', [MascotaController::class, 'update']);
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
    Route::get('/user/profile', [UserController::class, 'show']);
    Route::put('/user/profile', [UserController::class, 'update']);
});


//Rutas Administrador
Route::post('loginadmin', [AdminController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::post('registraradmin', [AdminController::class, 'register']);
    Route::post('logoutadmin', [AdminController::class, 'logout']);
    Route::get('admin/mascotas', [MascotaController::class, 'indexAdmin']);
    Route::get('admin/servicios', [ServiceController::class, 'index']);
    Route::get('admin/citas/pendientes', [CitaController::class, 'getCitasPendientesAdmin']);
    Route::patch('admin/citas/{id}/cancelar', [CitaController::class, 'cancelAdmin']);
    Route::patch('admin/citas/{id}/completar', [CitaController::class, 'completarAdmin']);
    Route::get('admin/citascompletadas', [CitaController::class, 'getCitasCompletadasAdmin']);
    Route::get('admin/user/profile', [AdminController::class, 'show']);
    Route::put('admin/user/profile', [AdminController::class, 'update']);
    //Servicios
    Route::post('admin/servicios', [ServiceController::class, 'store']);
    Route::get('admin/servicios/{id}', [ServiceController::class, 'show']);
    Route::put('admin/servicios/{id}', [ServiceController::class, 'update']);
    Route::delete('admin/servicios/{id}', [ServiceController::class, 'destroy']);
});