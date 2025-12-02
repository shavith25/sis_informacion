<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImagenMapaToZonasHistorialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('zonas_historial', function (Blueprint $table) {
            $table->longText('imagen_mapa')->nullable()->after('tipo_coordenada');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('zonas_historial', function (Blueprint $table) {
            $table->dropColumn('imagen_mapa');
        });

    }
}
