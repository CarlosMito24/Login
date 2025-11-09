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
            'nombres' => 'Melissa',
            'apellidos' => 'Fuentes',
            'telefono' => '6025-4852',
            'fecha_nacimiento' => '2004-08-28',
            'email' => 'melissa.elena24@itca.edu.sv',
            'password' => bcrypt('12345678'),
        ]);

        User::create([
            'nombres' => 'David',
            'apellidos' => 'Abarca',
            'telefono' => '7375-2000',
            'fecha_nacimiento' => '2005-01-01',
            'email' => 'josue.abarca24@itca.edu.sv',
            'password' => bcrypt('12345678'),
        ]);

        User::create([
            'nombres' => 'Edgardo',
            'apellidos' => 'Tobar',
            'telefono' => '7307-1626',
            'fecha_nacimiento' => '2005-01-01',
            'email' => 'edgardo.tobar24@itca.edu.sv',
            'password' => bcrypt('12345678'),
        ]);
    }
}
