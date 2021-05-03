<?php

namespace App\VendingMachine\Machine;

use App\VendingMachine\Machine\Actions\PurchaseAction;
use App\VendingMachine\Machine\Actions\RefundAction;
use Illuminate\Routing\Router;

class Routes
{
    /**
     * @var Router
     */
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function __invoke()
    {
        $this->router->group(['prefix' => 'api/vending-machines'], function (Router $router) {
            $router->post('purchase', PurchaseAction::class);
            $router->post('refund', RefundAction::class);
        });
    }
}
