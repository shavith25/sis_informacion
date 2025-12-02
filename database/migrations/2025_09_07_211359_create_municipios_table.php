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
        Schema::create('municipios', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_provincia');

            $table->string('nombre');
            $table->text('geometria')->nullable();   
            $table->string('tipo_geometria')->nullable(); 
            $table->timestamps();

            $table->foreign('id_provincia')
                  ->references('id')
                  ->on('provincias')
                  ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('municipios');
    }
};
