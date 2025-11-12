<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Mascota;

class MascotasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Mascota::create([
            'user_id' => 1,
            'nombre' => 'Luna',
            'especie' => 'Perro',
            'raza' => 'Chihuahua',
            'edad' => 1,
            'imagen' => '../images/mascotas/luna.jpg'
        ]);

        Mascota::create([
            'user_id' => 1,
            'nombre' => 'Lazzy',
            'especie' => 'Perro',
            'raza' => '',
            'edad' => 3,
            'imagen' => '../images/default/default.png',
        ]);
    }
}
