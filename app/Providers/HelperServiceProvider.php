<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Charger les fichiers de helpers si nécessaire
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Pas besoin de bootstrapping pour les helpers
    }
}
