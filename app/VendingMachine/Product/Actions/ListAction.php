<?php

namespace App\VendingMachine\Product\Actions;

use App\VendingMachine\Product\Queries\ListProducts;
use App\VendingMachine\Product\Resources\ProductResource;

class ListAction
{
    public function __invoke(ListProducts $listProducts)
    {
        $products = $listProducts->fetch();

        return ProductResource::collection($products);
    }
}
