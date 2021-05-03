<?php

namespace App\VendingMachine\Purchase\Actions;

use App\VendingMachine\Purchase\Queries\ListPurchases;
use App\VendingMachine\Purchase\Resources\PurchaseResource;

class ListAction
{
    public function __invoke(ListPurchases $listPurchases)
    {
        $purchases = $listPurchases->fetch();

        return PurchaseResource::collection($purchases);
    }
}
