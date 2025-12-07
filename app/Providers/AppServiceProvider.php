<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\ViewComposers\NotificationComposer;
use App\Models\User;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configurar Carbon en español
        Carbon::setLocale('es');

        // Registrar el View Composer SOLO para el layout principal (no todas las vistas)
        // Esto evita ejecutar consultas en cada componente/parcial
        View::composer(['layouts.app', 'layouts.navigation'], NotificationComposer::class);

    }
}
