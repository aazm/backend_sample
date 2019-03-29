<?php

namespace Turing\Providers;

use Illuminate\Support\ServiceProvider;
use Turing\Decorators\CachingProductServiceDecorator;
use Turing\Services\Impl\ProductService;
use Turing\Services\ProductServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ProductServiceInterface::class, function($app) {
            return new CachingProductServiceDecorator(new ProductService());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
