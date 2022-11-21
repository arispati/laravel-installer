<?php

namespace Arispati\LaravelInstaller;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class LaravelInstallerProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // load the views
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'installer');
        // load routes
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/config.php' => App::configPath('installer.php'),
            ], 'config');
        }
    }

    /**
     * Register services
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/config.php', 'installer');
    }

    /**
     * Register available routes
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'middleware' => ['web'],
            'prefix' => 'installer',
            'as' => 'installer.'
        ], function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/installer.php');
        });
    }
}
