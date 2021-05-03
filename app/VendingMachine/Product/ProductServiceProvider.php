<?php

namespace App\VendingMachine\Product;

use Illuminate\Support\ServiceProvider;

class ProductServiceProvider extends ServiceProvider
{
    public function boot()
    {
        (new Routes($this->app['router']))();
    }
}
