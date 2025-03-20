<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servizio;

class HomeController extends Controller
{
    public function home()
    {
        // Obtener estadísticas básicas de los servicios
        $totalServicios = Servizio::count();
        $totalGasto = Servizio::sum('totale_fattura');
        $serviciosPorTipo = Servizio::select('servizio', \DB::raw('count(*) as total'))
                                    ->groupBy('servizio')
                                    ->pluck('total', 'servizio');

        return view('dashboard', compact('totalServicios', 'totalGasto', 'serviciosPorTipo'));
    }
}