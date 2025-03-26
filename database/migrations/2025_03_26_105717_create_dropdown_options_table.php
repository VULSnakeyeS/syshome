<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDropdownOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('dropdown_options', function (Blueprint $table) {
            $table->id();
            $table->string('dropdown_name'); // Nombre del dropdown
            $table->string('option_value'); // Valor de la opción
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dropdown_options');
    }
};
