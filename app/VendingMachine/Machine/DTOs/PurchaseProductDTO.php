<?php

namespace App\VendingMachine\Machine\DTOs;

use App\VendingMachine\Product\Models\Product;

class PurchaseProductDTO
{
    /**
     * @var Product
     */
    private Product $product;
    private float $amount;

    public function __construct(Product $product, float $amount)
    {
        $this->product = $product;
        $this->amount = $amount;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }
}
