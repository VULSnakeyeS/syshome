<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('comida', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animale_id')->constrained('animali')->onDelete('cascade');
            $table->string('marca');
            $table->string('tipo'); // Crocchette, Umido, Snack
            $table->string('nome');
            $table->integer('quantita'); // En KG o unidades
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('comida');
    }
};
