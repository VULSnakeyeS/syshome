<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servizio extends Model
{
    use HasFactory;

    protected $table = 'servizi'; // ✅ Nombre correcto de la tabla

    protected $fillable = [
        'servizio',
        'bol_fatt',
        'totale_fattura',
        'data_fattura',
        'data_scadenza',
        'pdf_path',
        'commenti'
    ];
}
