<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servizio;

class HomeController extends Controller
{
    public function home()
    {
        // Obtener estadísticas básicas de los servicios
        $totalServizi = Servizio::count();
        $totaleSpesa = Servizio::sum('totale_fattura');
        $serviziPerTipo = Servizio::select('servizio', \DB::raw('count(*) as totale'))
                                    ->groupBy('servizio')
                                    ->pluck('totale', 'servizio');

        return view('dashboard', [
            'totalServizi' => $totalServizi,
            'totaleSpesa' => $totaleSpesa,
            'serviziPerTipo' => $serviziPerTipo,
        ]);
    }
}