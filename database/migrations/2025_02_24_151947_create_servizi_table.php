<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('servizi', function (Blueprint $table) {
            $table->id();
            $table->enum('servizio', ['Acqua', 'Gas', 'Internet', 'Luce']);
            $table->string('bol_fatt', 50);
            $table->decimal('totale_fattura', 20, 2);
            $table->date('data_fattura');
            $table->date('data_scadenza');
            $table->string('pdf_path')->nullable();
            $table->text('commenti')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servizi');
    }
};

