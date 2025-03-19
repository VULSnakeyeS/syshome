<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingListItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome', 'marca', 'categoria', 'quantita', 
        'immagine', 'purchased', 'prodotto_id'
    ];

    protected $casts = [
        'purchased' => 'boolean',
    ];

    public function prodotto()
    {
        return $this->belongsTo(Prodotto::class);
    }
}