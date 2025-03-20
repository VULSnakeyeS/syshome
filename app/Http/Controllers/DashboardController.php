<?php

namespace App\Http\Controllers;

use App\Models\Servizio;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiche per tipo di servizio (per grafico a barre)
        $servicioStats = Servizio::select('servizio', DB::raw('SUM(totale_fattura) as totale'))
            ->groupBy('servizio')
            ->get();

        // Dati per l'anno corrente (grafico a linee annuale)
        $yearData = $this->getYearlyData();
        
        // Dati per il mese corrente (grafico a barre mensile)
        $monthData = $this->getMonthlyData();
        
        // Dati per la settimana corrente (grafico a linee settimanale)
        $weekData = $this->getWeeklyData();
        
        return view('dashboard', compact('servicioStats', 'yearData', 'monthData', 'weekData'));
    }
    
    private function getYearlyData()
    {
        $currentYear = Carbon::now()->year;
        
        $yearlyData = Servizio::select(
                DB::raw('MONTH(data_fattura) as mese'),
                DB::raw('SUM(totale_fattura) as totale')
            )
            ->whereYear('data_fattura', $currentYear)
            ->groupBy(DB::raw('MONTH(data_fattura)'))
            ->orderBy('mese')
            ->get();
            
        // Creare array con tutti i mesi
        $data = [];
        $labels = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->locale('it')->format('M');
            $labels[] = $monthName;
            
            $monthData = $yearlyData->firstWhere('mese', $i);
            $data[] = $monthData ? round($monthData->totale, 2) : 0;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    private function getMonthlyData()
    {
        $currentYear = Carbon::now()->year;
        $currentMonth = Carbon::now()->month;
        
        $monthlyData = Servizio::select(
                'servizio',
                DB::raw('SUM(totale_fattura) as totale')
            )
            ->whereYear('data_fattura', $currentYear)
            ->whereMonth('data_fattura', $currentMonth)
            ->groupBy('servizio')
            ->get();
            
        return [
            'labels' => $monthlyData->pluck('servizio')->toArray(),
            'data' => $monthlyData->pluck('totale')->map(function($value) {
                return round($value, 2);
            })->toArray()
        ];
    }
    
    private function getWeeklyData()
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $weeklyData = Servizio::select(
                DB::raw('DATE(data_fattura) as data'),
                DB::raw('SUM(totale_fattura) as totale')
            )
            ->whereBetween('data_fattura', [$startOfWeek, $endOfWeek])
            ->groupBy(DB::raw('DATE(data_fattura)'))
            ->orderBy('data')
            ->get();
        
        // Creare array con tutti i giorni della settimana
        $data = [];
        $labels = [];
        
        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $dateStr = $date->format('Y-m-d');
            $dayName = $date->locale('it')->format('D');
            
            $labels[] = $dayName;
            
            $dayData = $weeklyData->firstWhere('data', $dateStr);
            $data[] = $dayData ? round($dayData->totale, 2) : 0;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}