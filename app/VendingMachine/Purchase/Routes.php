<?php

namespace App\VendingMachine\Purchase;

use App\VendingMachine\Purchase\Actions\ListAction;
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
        $this->router->group(['prefix' => 'api/purchases'], function (Router $router) {
            $router->get('/', ListAction::class);
        });
    }
}
