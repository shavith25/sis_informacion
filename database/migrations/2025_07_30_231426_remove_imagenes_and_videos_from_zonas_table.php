<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveImagenesAndVideosFromZonasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
    {
        Schema::table('zonas', function (Blueprint $table) {
            $table->dropColumn(['imagenes', 'videos']);
        });
    }

    public function down()
    {
        Schema::table('zonas', function (Blueprint $table) {
            $table->text('imagenes')->nullable(); 
            $table->text('videos')->nullable();   
        });
    }
        
}
