<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Http\ViewComposers\NotificationComposer;
use App\Models\User;

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
        // Registrar el View Composer para notificaciones
        View::composer('*', NotificationComposer::class);

        // Binding personalizado para rutas de students
        Route::model('student', User::class);
    }
}
