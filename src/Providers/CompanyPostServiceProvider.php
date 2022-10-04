<?php

namespace Companypost\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;

class CompanyPostServiceProvider extends ServiceProvider
{
    protected $commands = [
        //
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../../routes/admin.php');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $router = $this->app['router'];

        $this->app->register(RepositoryServiceProvider::class);

        if ($this->app->runningInConsole()) {
            $this->publishResources();
        }
    }

    protected function publishResources()
    {
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('/'),
        ], 'database/migrations');

        $this->publishes([
            __DIR__ . '/../database/seeds' => database_path('/'),
        ], 'database/seeds');
    }
}

