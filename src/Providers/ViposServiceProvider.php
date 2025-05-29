<?php

namespace Zplus\Vipos\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ViposServiceProvider extends ServiceProvider
{    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerConfig();
    }    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/web.php');
        
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'vipos');
        
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'vipos');
        
        // Publish assets
        $this->publishes([
            __DIR__.'/../Resources/assets' => public_path('packages/Zplus/vipos/assets'),
        ], 'vipos-assets');
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
