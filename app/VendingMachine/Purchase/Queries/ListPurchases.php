<?php

namespace App\VendingMachine\Purchase\Queries;

use App\VendingMachine\Purchase\Models\Purchase;

class ListPurchases
{
    /**
     * @var Purchase
     */
    private $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function fetch()
    {
        return $this->purchase->newQuery()
            ->join('products', 'products.id', 'purchases.product_id')
            ->select(
                'purchases.*',
                'products.name as product_name'
            )
            ->get();
    }
}
