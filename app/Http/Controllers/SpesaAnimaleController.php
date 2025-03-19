<?php

namespace App\Http\Controllers;

use App\Models\InventarioAnimale;
use App\Models\Animale;
use Illuminate\Http\Request;

class SpesaAnimaleController extends Controller
{
    public function index(Request $request)
    {
        // Obtener todos los animales para el filtro
        $animali = Animale::all();

        // Obtener los datos del filtro
        $animale_id = $request->input('animale_id');
        $data_inizio = $request->input('data_inizio');
        $data_fine = $request->input('data_fine');

        // Base de consulta desde `inventarioanimali`
        $query = InventarioAnimale::query();

        // Filtrar por animal si está seleccionado
        if ($animale_id) {
            $query->where('animale_id', $animale_id);
        }

        // Filtrar por rango de fechas si están seleccionadas
        if ($data_inizio && $data_fine) {
            $query->whereBetween('data_acquisto', [$data_inizio, $data_fine]);
        }

        // Obtener los últimos 10 productos comprados por cada animal
        $spese = $query->orderByDesc('data_acquisto')->paginate(10);

        // Calcular el total gastado en los registros visibles
        $totaleSpese = $spese->sum(function ($spesa) {
            return $spesa->prezzo * $spesa->quantita;
        });

        return view('speseanimali.index', compact('animali', 'spese', 'totaleSpese', 'animale_id', 'data_inizio', 'data_fine'));
    }
}

