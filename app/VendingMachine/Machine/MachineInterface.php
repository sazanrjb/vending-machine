<?php

namespace App\VendingMachine\Machine;

use App\VendingMachine\Machine\DTOs\PurchaseProductDTO;
use App\VendingMachine\Product\Models\Product;
use App\VendingMachine\Purchase\Models\Purchase;

interface MachineInterface
{
    /**
     * Purchase Product
     *
     * @param PurchaseProductDTO $purchaseProductDTO
     * @return mixed
     */
    public function purchaseProduct(PurchaseProductDTO $purchaseProductDTO): Product;

    /**
     * Refund Product
     *
     * @param Purchase $purchase
     * @return mixed
     */
    public function refundProduct(Purchase $purchase): Product;
}
