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
        Schema::create('comentario_participaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('comentario');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('likes')->default(0);
            $table->boolean('aprobado')->default(false);
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('comentario_participaciones')
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
        Schema::dropIfExists('comentario_participaciones');
    }
};
