<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
        // Force URL generation to use the APP_URL with the custom port (8889)
        URL::forceRootUrl(config('app.url'));
        
        // Define admin gate
        Gate::define('admin', function (User $user) {
            return $user->is_admin;
        });
    }
}
