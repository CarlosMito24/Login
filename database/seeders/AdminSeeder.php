<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\admin;


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        admin::create([
            'nombres' => 'Carlos',
            'apellidos' => 'Mito',
            'telefono' => '6190-6881',
            'fecha_nacimiento' => '2005-03-24',
            'email' => 'carlosalfonsomito@gmail.com',
            'password' => bcrypt('12345678'),
        ]);
    }
}
