<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Pages\Dashboard;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Support\Facades\FilamentAuthentication;
use Filament\Support\Facades\FilamentRouter;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Ici, nous allons compléter l'enregistrement des routes Filament
        // pour nous assurer que les routes d'authentification POST sont correctement définies
        
        $this->app->resolving('filament', function () {
            \Filament\Facades\Filament::registerRenderHook(
                'panels::body.end',
                fn (): string => view('filament.hooks.body-end')->render(),
            );
        });
        
        $this->ensureFilamentAuthRoutes();
    }
    
    /**
     * S'assure que les routes d'authentification POST sont correctement enregistrées
     */
    protected function ensureFilamentAuthRoutes(): void
    {
        // Si vous utilisez laravel/ui en parallèle, certaines routes peuvent entrer en conflit
        // Nous nous assurons que les routes POST sont correctement définies pour admin/login
        
        if (! $this->app->routesAreCached()) {
            \Illuminate\Support\Facades\Route::middleware([
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
                ->name('filament.')
                ->prefix('admin')
                ->group(function () {
                    // Ajouter explicitement la route POST pour admin/login
                    \Illuminate\Support\Facades\Route::post('login', function () {
                        return app()->make(\Filament\Http\Controllers\Auth\AuthenticatedSessionController::class)->store();
                    })->name('auth.login');
                });
        }
    }
}
