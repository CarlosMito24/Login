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
        Schema::table('citas', function (Blueprint $table) {
            if (Schema::hasColumn('citas', 'descripcion')) {
                $table->dropColumn('descripcion');
            }

            $table->foreignId('mascota_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('servicios_id')->nullable()->constrained('servicios')->onDelete('cascade');
            $table->foreignId('estado_id')->nullable()->constrained('estado_citas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropForeign(['mascota_id']);
            $table->dropForeign(['servicios_id']);
            $table->dropForeign(['estado_id']);
            $table->dropColumn(['mascota_id', 'servicios_id', 'estado_id']);
        });
    }
};
