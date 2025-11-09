<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            
            // Llaves foráneas que usan el método corto (constrained)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Campos de datos
            $table->date('fecha');
            $table->time('hora');
            
            // --- Claves Foráneas de la Cita (Usamos el método de dos pasos para más control) ---
            
            // Mascota: Definimos el campo y la restricción
            $table->foreignId('mascota_id')->nullable();
            $table->foreign('mascota_id')->references('id')->on('mascotas')->onDelete('cascade');

            // Servicios: Definimos el campo y la restricción
            $table->foreignId('servicios_id')->nullable();
            $table->foreign('servicios_id')->references('id')->on('servicios')->onDelete('cascade');
            
            // Estado: Definimos el campo (con default) y la restricción
            $table->foreignId('estado_id')->nullable()->default(1);
            $table->foreign('estado_id')->references('id')->on('estado_citas')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('citas');
    }
};