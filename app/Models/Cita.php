<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'fecha',
        'hora',
        'mascota_id',
        'servicios_id',
        'estado_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mascota()
    {
        return $this->belongsTo(\App\Models\Mascota::class);
    }

    public function servicio()
    {
        return $this->belongsTo(\App\Models\Servicios::class, 'servicios_id');
    }

    public function estado()
    {
        return $this->belongsTo(\App\Models\EstadoCita::class, 'estado_id');
    }
}
