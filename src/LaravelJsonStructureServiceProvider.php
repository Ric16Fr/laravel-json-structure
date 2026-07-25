<?php

declare(strict_types=1);

namespace LaravelJsonStructure\LaravelJsonStructure;

use Illuminate\Support\ServiceProvider;

class LaravelJsonStructureServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/json_structure.php', 'json_structure');

        $this->app->singleton(LaravelJsonStructure::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->app->make(LaravelJsonStructure::class)->boot();

        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__.'/../config/json_structure.php' => config_path('json_structure.php'),
        ], ['json_structure', 'json_structure_config']);
    }
}
