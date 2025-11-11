<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\EstadoCita;


class EstadoCitaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EstadoCita::create([
            'nombre' => 'Pendiente',
        ]);

        EstadoCita::create([
            'nombre' => 'Completada',
        ]);

        EstadoCita::create([
            'nombre' => 'Cancelada',
        ]);
    }
}
