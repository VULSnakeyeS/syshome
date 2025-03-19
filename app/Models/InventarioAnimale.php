<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Animale;

class InventarioAnimale extends Model
{
    use HasFactory;

    protected $table = 'inventario_animali';

    protected $fillable = [
        'animale_id',
        'categoria',
        'marca',
        'nome',
        'quantita',
        'unita_misura',
        'data_acquisto',
        'data_scadenza',
        'prezzo',
        'foto'
    ];

    protected $casts = [
        'data_acquisto' => 'date',
        'data_scadenza' => 'date',
        'prezzo' => 'decimal:2',
    ];

    // ✅ Relación con Animale
    public function animale()
    {
        return $this->belongsTo(Animale::class, 'animale_id');
    }

    // ✅ Métodos para obtener las categorías según la especie
    public static function categoriePerSpecie($specie)
    {
        return $specie === 'Cane' 
            ? ['Mangiare', 'Accessori', 'Medicine', 'Vari'] 
            : ['Mangiare', 'Sabbia', 'Accessori', 'Medicine', 'Vari'];
    }
}
