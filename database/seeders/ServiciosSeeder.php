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
            'nombre' => 'Consulta General',
            'descripcion' => 'Consulta general para atender enfermedades de tus mascotas',
            'precio' => 10.0,
            'imagen' => '../images/imagen_1.png'
        ]);

        Servicios::create([
            'nombre' => 'Chequeo General',
            'descripcion' => 'Chequeo de rutina para atender a tus mascotas',
            'precio' => 10.0,
            'imagen' => '../images/imagen_2.png'
        ]);

        Servicios::create([
            'nombre' => 'Vacunación',
            'descripcion' => 'Vacunación preventiva para tus mascotass',
            'imagen' => '../images/imagen_3.png'
        ]);

        Servicios::create([
            'nombre' => 'Baño y Corte',
            'descripcion' => 'Sesión de baño y corte de pelaje para tu mascota',
            'precio' => 15.0,
            'imagen' => '../images/imagen_4.png'
        ]);
    }
}
