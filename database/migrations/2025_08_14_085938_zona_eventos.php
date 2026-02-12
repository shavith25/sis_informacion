<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ZonaEventos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zona_eventos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_id')->constrained('zonas')->onDelete('cascade');
            
            $table->enum('tipo', ['incendio', 'avasallamiento', 'inundacion', 'sequia', 'loteamiento', 'afectacion_biodiversidad', 'otro']);
            $table->string('titulo', 255);
            $table->longText('descripcion')->nullable();
            $table->timestamp('fecha_evento')->nullable();
            $table->enum('estado', ['activo', 'en_proceso', 'resuelto'])->default('activo');

            $table->longText('coordenadas')->nullable();
            $table->string('tipo_coordenada', 128);
            
            $table->timestamps();

            $table->index('zona_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::dropIfExists('zona_eventos');
    }
}
