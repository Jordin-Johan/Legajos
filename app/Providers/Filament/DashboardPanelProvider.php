<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Widgets\DashboardResumenWidget;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Saade\FilamentFullCalendar\FilamentFullCalendarPlugin;
use Illuminate\Support\Facades\Auth;
use Filament\Navigation\UserMenuItem;



class DashboardPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->darkMode(true)
            ->favicon(asset('assets/images/morales.png'))
            ->brandLogoHeight('4rem')
            ->brandLogo(asset('assets/images/Logo_mdm.svg'))
            ->id('dashboard')
            ->path('dashboard')
            ->userMenuItems([
                UserMenuItem::make()
                    ->label(fn() => '' . (Auth::user()?->getRoleNames()->first() ?? 'Sin rol'))
                    ->icon('heroicon-o-user'),
                UserMenuItem::make()
                    ->label(fn() => '' . (Auth::user()?->email ?? 'Sin email'))
                    ->icon('heroicon-o-envelope'),
            ])
            ->font('Poppins')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Sky,
                'sidebar' => [
                    500 => '#38b6ff',
                ],
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'secondary' => Color::Indigo,
            ])

            // 1. Inyectar estilos personalizados
            ->renderHook('panels::head.end', fn() => '
            
                <style>
                    /* Estilos para modo claro (default) */
                    .fi-sidebar {
                        background-color: #ffffff; /* sidebar blanco en claro */
                    }

                    .fi-sidebar-item-label,
                    .fi-sidebar-group-label,
                    .fi-sidebar-item-icon {
                        color: #1e293b; /* texto gris oscuro */
                        transition: color 0.2s ease-in-out;
                    }

                    /* Hover modo claro: resalta texto con color primario */
                    .fi-sidebar-item:hover .fi-sidebar-item-label,
                    .fi-sidebar-item:hover .fi-sidebar-item-icon {
                        color: #38b6ff; /* rojo (primary) */
                    }

                    /* Item activo modo claro */
                    .fi-sidebar-item-active .fi-sidebar-item-label,
                    .fi-sidebar-item-active .fi-sidebar-item-icon {
                        color: #38b6ff;
                        font-weight: 600;
                    }

                    /* --- Estilos para modo oscuro usando selector .dark --- */
                    .dark .fi-sidebar {
                        background-color: #000000; /* sidebar oscuro en modo oscuro */
                    }

                    .dark .fi-sidebar-item-label,
                    .dark .fi-sidebar-group-label,
                    .dark .fi-sidebar-item-icon {
                        color: #ffffff;
                    }

                    .dark .fi-sidebar-item:hover .fi-sidebar-item-label,
                    .dark .fi-sidebar-item:hover .fi-sidebar-item-icon {
                        color: #38b6ff; /* rojo para hover en oscuro */
                    }

                    .dark .fi-sidebar-item-active .fi-sidebar-item-label,
                    .dark .fi-sidebar-item-active .fi-sidebar-item-icon {
                        color: #38b6ff;
                        font-weight: 600;
                    }

                    /* Cabecera del sidebar (logo) */
                    .fi-sidebar-header {
                        background-color: #ffffff;
                    }

                    .dark .fi-sidebar-header {
                        background-color: #111827;
                    }

                    /* Estilo del Footer */
                    
                </style>

            ')

            // 2. Inyectar el JS con NProgress
            ->renderHook('panels::scripts.before', fn(): string => <<<'HTML'
              <script type="module" src="/build/assets/app.js"></script>
            HTML)
            
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,

            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentFullCalendarPlugin::make(),
            ]);
    }
    public static function getWidgets(): array
    {
        return [
            DashboardResumenWidget::class,
        ];
    }
}
