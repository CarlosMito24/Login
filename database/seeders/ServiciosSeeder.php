<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Servicios;

class ServiciosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Servicios::create([
            'nombre' => 'Consulta Veterinaria General',
            'descripcion' => 'Evaluación integral de la salud de tu mascota, diagnóstico y plan de tratamiento para enfermedades comunes.',
            'precio' => 20.0,
            'imagen' => '../images/servicios/imagen_1.png'
        ]);

        Servicios::create([
            'nombre' => 'Chequeo General de Rutina',
            'descripcion' => 'Examen físico completo y revisión preventiva. Ideal para asegurar el bienestar y detectar problemas tempranamente.',
            'precio' => 15.0,
            'imagen' => '../images/servicios/imagen_2.png'
        ]);

        Servicios::create([
            'nombre' => 'Programa de Vacunación Esencial',
            'descripcion' => 'Aplicación de vacunas necesarias según la edad y especie. ¡Protégete contra enfermedades infecciosas!',
            'precio' => null, 
            'imagen' => '../images/servicios/imagen_3.png'
        ]);

        Servicios::create([
             'nombre' => 'Spa Canino: Baño y Corte Premium',
            'descripcion' => 'Sesión completa de baño, secado, corte de pelaje, limpieza de oídos y corte de uñas.',
            'precio' => 25.0,
            'imagen' => '../images/servicios/imagen_4.png'
        ]);
    }
}
