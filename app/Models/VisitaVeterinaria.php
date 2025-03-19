<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Animale; // ✅ Se importa correctamente el modelo Animale

class VisitaVeterinaria extends Model
{
    use HasFactory;

    protected $table = 'visite_veterinarie';

    protected $fillable = [
        'animale_id',
        'data_visita',
        'tipo',
        'descrizione',
        'documento'
    ];

    // ✅ Relación corregida con Animale
    public function animale()
    {
        return $this->belongsTo(Animale::class, 'animale_id');
    }
}

