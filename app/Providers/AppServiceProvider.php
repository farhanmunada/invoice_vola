<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Share shop settings with all views (for Sidebar name/logo)
        try {
            $globalSetting = \App\Models\Setting::first();
            view()->share('globalSetting', $globalSetting);
        } catch (\Exception $e) {
            // Fallback during migration
        }
    }
}
