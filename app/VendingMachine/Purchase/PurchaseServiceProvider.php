<?php

namespace App\VendingMachine\Purchase;

use Illuminate\Support\ServiceProvider;

class PurchaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        (new Routes($this->app['router']))();
    }
}
