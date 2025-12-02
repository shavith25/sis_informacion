<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE departamentos ALTER COLUMN geometria TYPE JSONB USING geometria::jsonb;');
        DB::statement('ALTER TABLE provincias ALTER COLUMN geometria TYPE JSONB USING geometria::jsonb;');
        DB::statement('ALTER TABLE municipios ALTER COLUMN geometria TYPE JSONB USING geometria::jsonb;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE departamentos ALTER COLUMN geometria TYPE TEXT;');
        DB::statement('ALTER TABLE provincias ALTER COLUMN geometria TYPE TEXT;');
        DB::statement('ALTER TABLE municipios ALTER COLUMN geometria TYPE TEXT;');
    }
};
