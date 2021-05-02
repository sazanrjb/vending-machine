<?php

namespace App\VendingMachine\Machine;

use App\VendingMachine\Machine\Actions\PurchaseAction;
use Illuminate\Routing\Router;

class Routes
{
    /**
     * @var Router
     */
    private Router $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function __invoke()
    {
        $this->router->group(['prefix' => 'api/vending-machines'], function (Router $router) {
            $router->post('purchase', PurchaseAction::class);
        });
    }
}
