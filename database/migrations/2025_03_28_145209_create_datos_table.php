<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos', function (Blueprint $table) {
            $table->id();
            $table->string('flora_fauna', 512);
            $table->string('extension', 512);
            $table->string('poblacion', 512);
            $table->string('provincia', 512);
            $table->string('especies_peligro', 512);
            $table->string('otros_datos', 512);
            $table->foreignId('zona_id')->constrained('zonas')->onDelete('cascade');
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
        Schema::dropIfExists('datos');
    }
}
