<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('animali', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('specie');
            $table->string('razza')->nullable();
            $table->date('data_nascita')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::create('inventario_cibo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animale_id')
                ->constrained('animali')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('marca');
            $table->string('tipo');
            $table->string('nome');
            $table->string('foto')->nullable();
            $table->timestamps();
        });

        Schema::create('spese_animali', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animale_id')
                ->constrained('animali')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('categoria');
            $table->decimal('importo', 10, 2);
            $table->text('descrizione')->nullable();
            $table->string('documento')->nullable(); // Para guardar PDFs de gastos
            $table->date('data_spesa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spese_animali');
        Schema::dropIfExists('inventario_cibo');
        Schema::dropIfExists('animali');
    }
};

