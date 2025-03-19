<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shopping_list_items', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('marca')->nullable();
            $table->string('categoria');
            $table->integer('quantita')->default(1);
            $table->string('immagine')->nullable();
            $table->boolean('purchased')->default(false);
            $table->foreignId('prodotto_id')->nullable()->constrained('prodotti')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shopping_list_items');
    }
};
