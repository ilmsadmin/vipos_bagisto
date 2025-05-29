<?php

namespace Zplus\Vipos\Providers;

use Illuminate\Support\ServiceProvider;

class ViposServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'vipos');
        
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'vipos');
        
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
    }

    /**
     * Register package config.
     */
    protected function registerConfig(): void
    {
        $this->mergeConfigFrom(
            dirname(__DIR__).'/Config/menu.php',
            'menu.admin'
        );
    }
}
