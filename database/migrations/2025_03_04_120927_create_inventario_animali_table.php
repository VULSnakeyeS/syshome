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
        Schema::create('inventario_animali', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animale_id')->constrained('animali')->onDelete('cascade'); // Relación con la tabla 'animali'
            $table->string('categoria'); // Mangiare, Accessori, Medicine, Vari, Sabbia (solo para gatos)
            $table->string('marca')->nullable();
            $table->string('nome');
            $table->integer('quantita')->default(1);
            $table->string('unita_misura')->default('pezzi'); // Especifica si es kg, pezzi, litri, etc.
            $table->date('data_acquisto')->nullable();
            $table->date('data_scadenza')->nullable();
            $table->decimal('prezzo', 10, 2)->nullable();
            $table->string('foto')->nullable(); // Imagen del producto
            $table->timestamps();
        });
    }

    /**
     * Revertir la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_animali');
    }
};
