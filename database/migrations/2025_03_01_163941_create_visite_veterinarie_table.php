<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecutar la migración.
     */
    public function up(): void
    {
        Schema::create('visite_veterinarie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animale_id')
                ->constrained('animali')
                ->onDelete('cascade'); // ✅ Relación correcta con 'animali'

            $table->date('data_visita'); // ✅ Fecha de la visita
            $table->string('tipo'); // ✅ Tipo de visita (Veterinario o Peluquería)
            $table->text('descrizione')->nullable(); // ✅ Descripción opcional
            $table->string('documento')->nullable(); // ✅ Archivo adjunto (PDF o imagen)
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('visite_veterinarie');
    }
};
