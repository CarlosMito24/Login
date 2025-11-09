<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Cita;

class CitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Cita::create([
            'user_id' => 1,
            'fecha' => '2025-11-15',
            'hora' => '09:00',
            'mascota_id' => 1,
            'servicios_id' => 1,
            'estado_id' => 1
        ]);

        Cita::create([
            'user_id' => 1,
            'fecha' => '2025-11-09',
            'hora' => '09:00',
            'mascota_id' => 2,
            'servicios_id' => 3,
            'estado_id' => 2
        ]);
    }
}
