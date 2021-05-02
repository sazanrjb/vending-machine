<?php

namespace App\VendingMachine\Purchase;

use App\VendingMachine\Core\Exceptions\ResourceNotFoundException;
use App\VendingMachine\Machine\DTOs\PurchaseProductDTO;
use App\VendingMachine\Product\Models\Product;
use App\VendingMachine\Purchase\Models\Purchase;

class Manager
{
    /**
     * @var Purchase
     */
    private Purchase $purchase;

    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function find(int $purchaseId): Purchase
    {
        $purchaseInfo = $this->purchase->newQuery()->find($purchaseId);

        throw_if(!$purchaseInfo, new ResourceNotFoundException(
            trans('not_found', [
                'Entity' => 'purchase information'
            ])
        ));

        return $purchaseInfo;
    }

    public function create(PurchaseProductDTO $purchaseProductDTO): Purchase
    {
        $product = $purchaseProductDTO->getProduct();
        $purchaseInfo = $this->purchase->associateProduct($product)
            ->forceFill([
                'price' => $product->price,
                'amount_paid' => $purchaseProductDTO->getAmount(),
                'amount_returned' => $purchaseProductDTO->getAmount() - $product->price
            ]);

        $purchaseInfo->save();

        return $purchaseInfo;
    }

    public function delete(Purchase $purchase): Purchase
    {
        $purchase->delete();

        return $purchase;
    }
}
