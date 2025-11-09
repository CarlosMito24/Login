<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UsuariosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'nombres' => 'Carlos',
            'apellidos' => 'Mito',
            'telefono' => '6190-6881',
            'fecha_nacimiento' => '2005-03-24',
            'email' => 'carlosalfonsomito@gmail.com',
            'password' => bcrypt('CarlosMito'),
        ]);

        User::create([
            'nombres' => 'Sofy',
            'apellidos' => 'Ventura',
            'telefono' => '7250-30544',
            'fecha_nacimiento' => '2005-03-24',
            'email' => 'sofyventura@gmail.com',
            'password' => bcrypt('CarlosMito'),
        ]);
    }
}
