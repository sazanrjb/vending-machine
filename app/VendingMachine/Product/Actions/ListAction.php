<?php

namespace App\VendingMachine\Product\Actions;

use App\VendingMachine\Core\ValueObjects\AmountType;
use App\VendingMachine\Product\Queries\ListProducts;
use App\VendingMachine\Product\Resources\ProductResource;
use App\VendingMachine\Amount\Manager as AmountManager;

class ListAction
{
    public function __invoke(ListProducts $listProducts, AmountManager $amountManager)
    {
        $products = $listProducts->fetch();

        return ProductResource::collection($products)->additional([
            'amount' => (float) $amountManager->findByType(AmountType::COIN)?->amount,
            'amount_limit' => config('vending_machine.max_amount')
        ]);
    }
}
