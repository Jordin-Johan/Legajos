<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Carbon;

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
        //
        FilamentView::registerRenderHook(
            'panels::auth.login.form.after',
            fn (): string => Blade::render('@vite(\'resources/css/bg-login.css\')'),
        );
        //fecha y hora peru
        Carbon::setLocale('es');
        config(['app.timezone' => 'America/Lima']);
        date_default_timezone_set('America/Lima');
        //paginacion tailwind
        Paginator::useTailwind();
    }
}
