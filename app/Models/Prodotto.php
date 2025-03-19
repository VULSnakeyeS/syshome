<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prodotto extends Model
{
    use HasFactory;

    // Especificar el nombre correcto de la tabla
    protected $table = 'prodotti';

    protected $fillable = [
        'nome',
        'barcode',
        'marca',
        'categoria',
        'quantita',
        'ubicazione',
        'immagine',
        'minimo_scorta',
        'note',
    ];
}