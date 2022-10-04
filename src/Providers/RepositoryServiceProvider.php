<?php

namespace Companypost\Providers;

use Illuminate\Support\ServiceProvider;

use Companypost\Repositories\Eloquents\PostRepository;
use Companypost\Repositories\Contracts\PostRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
