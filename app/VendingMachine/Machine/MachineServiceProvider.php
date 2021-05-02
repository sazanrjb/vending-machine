<?php

namespace App\VendingMachine\Machine;

use Illuminate\Support\ServiceProvider;

class MachineServiceProvider extends ServiceProvider
{
    public function boot()
    {
        (new Routes($this->app['router']))();
    }
}
