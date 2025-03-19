<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compito extends Model
{
    use HasFactory;

    protected $table = 'compiti'; // Nombre de la tabla en la BD

    protected $fillable = [
        'titolo',
        'descrizione',
        'data_compito',
        'assegnato_a',
        'completato',
    ];

    protected $casts = [
        'completato' => 'boolean', // Convertir automáticamente a true/false
    ];
}
