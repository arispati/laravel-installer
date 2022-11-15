<?php

namespace Arispati\LaravelInstaller;

use Arispati\LaravelInstaller\Http\Middleware\InternetAccess;
use Illuminate\Routing\Router;
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
    }

    /**
     * Register available routes
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        });
    }

    /**
     * Routes configurations
     *
     * @return array
     */
    protected function routeConfiguration(): array
    {
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('install-internet', InternetAccess::class);

        return [
            'prefix' => 'install',
            'middleware' => ['install-internet']
        ];
    }
}
