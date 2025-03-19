<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Compito;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Contar tareas pendientes y compartirlo con todas las vistas
        $compiti_pendenti_count = Compito::where('completato', false)->count();
        View::share('compiti_pendenti_count', $compiti_pendenti_count);
    }
}
