<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('animali', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('note'); // ✅ Agregar la columna foto después de 'note'
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('animali', function (Blueprint $table) {
            $table->dropColumn('foto'); // ✅ Eliminar la columna si se revierte la migración
        });
    }

};
