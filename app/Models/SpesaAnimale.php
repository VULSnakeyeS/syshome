<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpesaAnimale extends Model
{
    use HasFactory;

    protected $table = 'spese_animali';

    protected $fillable = ['animale_id', 'categoria', 'costo', 'descrizione', 'factura_pdf'];

    public function animale()
    {
        return $this->belongsTo(Animale::class);
    }
}
