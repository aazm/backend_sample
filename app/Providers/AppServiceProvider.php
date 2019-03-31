<?php

namespace Turing\Providers;

use Illuminate\Support\ServiceProvider;
use Turing\Decorators\CachingProductServiceDecorator;
use Turing\Services\CustomerServiceInterface;
use Turing\Services\Impl\CustomerService;
use Turing\Services\Impl\ProductService;
use Turing\Services\Impl\ShoppingCartService;
use Turing\Services\ProductServiceInterface;
use Turing\Services\ShoppingCartServiceInterface;

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
        $this->app->bind(CustomerServiceInterface::class, CustomerService::class);
        $this->app->bind(ShoppingCartServiceInterface::class, ShoppingCartService::class);


/*        $this->app->extend(ProductServiceInterface::class, function($service){
            return new CachingProductServiceDecorator($service);
        });*/


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
