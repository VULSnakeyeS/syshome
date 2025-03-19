<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('spese_animali', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animale_id')->constrained('animali')->onDelete('cascade');
            $table->string('categoria'); // Comida, Veterinario, Peluquería, Accesorios
            $table->decimal('costo', 8, 2);
            $table->date('data_spesa')->nullable(); // 📌 Nueva columna para la fecha de gasto (CORREGIDO)
            $table->text('descrizione')->nullable();
            $table->string('factura_pdf')->nullable(); // Archivo PDF de la factura
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('spese_animali');
    }
};
