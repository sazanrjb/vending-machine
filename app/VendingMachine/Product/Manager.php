<?php

namespace App\VendingMachine\Product;

use App\VendingMachine\Core\Exceptions\ResourceNotFoundException;
use App\VendingMachine\Product\Exceptions\ProductOutOfStockException;
use App\VendingMachine\Product\Models\Product;

class Manager
{
    /**
     * @var Product
     */
    private Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function find(int $productId): Product
    {
        /** @var Product $product */
        $product = $this->product->newQuery()->find($productId);

        throw_if(!$product, new ResourceNotFoundException(
            trans('general.not_found', [
                'Entity' => 'product'
            ])
        ));

        return $product;
    }

    public function addCount(Product $product): Product
    {
        $product->total = $product->total + 1;
        $product->save();

        return $product;
    }

    public function deductCount(Product $product): Product
    {
        $product->total = $product->total - 1;
        $product->save();

        return $product;
    }
}
