<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('prodotti', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('categoria');
            $table->integer('quantita')->default(0);
            $table->string('ubicazione')->nullable(); // Cucina, Sala, Stanza, Bagno
            $table->string('immagine')->nullable();
            $table->integer('minimo_scorta')->default(1);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prodotti');
    }
};