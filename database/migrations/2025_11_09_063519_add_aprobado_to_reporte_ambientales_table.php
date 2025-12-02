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
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->boolean('aprobado')->default(false)->after('contenido');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reportes_ambientales', function (Blueprint $table) {
            $table->dropColumn('aprobado');
        });
    }
};
