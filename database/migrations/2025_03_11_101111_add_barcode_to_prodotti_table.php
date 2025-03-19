<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('prodotti', function (Blueprint $table) {
            $table->string('barcode')->nullable()->after('nome')->unique();
        });
    }

    public function down()
    {
        Schema::table('prodotti', function (Blueprint $table) {
            $table->dropColumn('barcode');
        });
    }
};
