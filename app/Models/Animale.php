<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\VisitaVeterinaria;
use App\Models\InventarioAnimale;
use App\Models\SpesaAnimale;

class Animale extends Model
{
    use HasFactory;

    protected $table = 'animali';

    protected $fillable = [
        'nome', 
        'specie', 
        'razza', 
        'data_nascita', 
        'note', 
        'foto'
    ];

    protected $casts = [
        'data_nascita' => 'date',
    ];

    // ✅ Relación con Visite Veterinarie
    public function visiteVeterinarie()
    {
        return $this->hasMany(VisitaVeterinaria::class, 'animale_id');
    }

    // ✅ Relación con Inventario Animale
    public function inventario()
    {
        return $this->hasMany(InventarioAnimale::class, 'animale_id');
    }

    // ✅ Relación con Spese Animali (Gastos)
    public function spese()
    {
        return $this->hasMany(SpesaAnimale::class, 'animale_id');
    }
}




