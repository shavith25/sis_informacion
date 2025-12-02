<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ZonaEventoMedios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('zona_evento_medios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evento_id')->constrained('zona_eventos')->onDelete('cascade');
            $table->enum('tipo', ['imagen', 'video']);
            $table->string('url');
            $table->string('descripcion')->nullable();
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
        Schema::dropIfExists('zona_evento_medios');
    }
}
