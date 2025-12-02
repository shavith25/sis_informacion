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
        Schema::create('comunidad_archivos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->string('ruta_archivo');
            $table->string('mime_type', 50);
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
        Schema::dropIfExists('comunidad_archivos');
    }
};
