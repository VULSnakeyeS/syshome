<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('compiti', function (Blueprint $table) {
            $table->id();
            $table->string('titolo'); // Nombre del deber
            $table->text('descrizione')->nullable(); // Descripción
            $table->date('data_compito'); // Fecha en que debe hacerse
            $table->string('assegnato_a'); // A quién le toca hacerlo
            $table->boolean('completato')->default(0); // Estado de la tarea (0 = pendiente, 1 = completado)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compiti');
    }
};
