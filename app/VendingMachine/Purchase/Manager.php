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
    private Purchase $purchaseInformation;

    public function __construct(Purchase $purchaseInformation)
    {
        $this->purchaseInformation = $purchaseInformation;
    }

    public function find(int $purchaseInformationId): Purchase
    {
        $purchaseInfo = $this->purchaseInformation->find($purchaseInformationId);

        throw_if(!$purchaseInfo, new ResourceNotFoundException(
            trans('not_found', [
                'Entity' => 'purchase information'
            ])
        ));
    }

    public function create(PurchaseProductDTO $purchaseProductDTO): Purchase
    {
        $product = $purchaseProductDTO->getProduct();
        $purchaseInfo = $this->purchaseInformation->associateProduct($product)
            ->forceFill([
                'price' => $product->price,
                'amount_paid' => $purchaseProductDTO->getAmount(),
                'amount_returned' => $purchaseProductDTO->getAmount() - $product->price
            ]);

        $purchaseInfo->save();

        return $purchaseInfo;
    }

    public function delete(Purchase $purchaseInformation): Purchase
    {
        $purchaseInformation->delete();

        return $purchaseInformation;
    }
}
