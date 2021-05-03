<?php

namespace App\VendingMachine\Product\Queries;

use App\VendingMachine\Product\Models\Product;
use Illuminate\Database\Eloquent\Collection;

class ListProducts
{
    /**
     * @var Product
     */
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function fetch(): Collection
    {
        return $this->product->newQuery()
            ->select(
                'products.id',
                'products.name',
                'products.price',
                'products.total'
            )
            ->get();
    }
}
