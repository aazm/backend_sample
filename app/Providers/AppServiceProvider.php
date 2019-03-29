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

        $this->app->bind(ProductServiceInterface::class, ProductService::class);

        $this->app->extend(ProductServiceInterface::class, function($service){
            return new CachingProductServiceDecorator($service);
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
