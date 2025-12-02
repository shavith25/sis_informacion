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
        Schema::table('especies', function (Blueprint $table) {
            $table->unsignedBigInteger('zona_id')->nullable()->after('tipo');
            $table->foreign('zona_id')->references('id')->on('zonas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('especies', function (Blueprint $table) {
            $table->dropForeign(['zona_id']);
            $table->dropColumn('zona_id');
        });
    }
};
