<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('zonas_historial', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zona_id')->constrained('zonas')->onDelete('cascade');
            $table->json('coordenadas');
            $table->string('tipo_coordenada');
            $table->timestamps();

            $table->index('zona_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('zonas_historial');
    }
};
