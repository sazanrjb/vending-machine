<?php

namespace App\VendingMachine\Machine;

use App\VendingMachine\Core\ValueObjects\AmountType;
use App\VendingMachine\Machine\DTOs\PurchaseProductDTO;
use App\VendingMachine\Machine\Exceptions\InSufficientAmountException;
use App\VendingMachine\Machine\Exceptions\MaxAmountExceededException;
use App\VendingMachine\Product\Exceptions\ProductOutOfStockException;
use App\VendingMachine\Product\Manager as ProductManager;
use App\VendingMachine\Product\Models\Product;
use App\VendingMachine\Purchase\Manager as PurchaseManager;
use App\VendingMachine\Purchase\Models\Purchase;
use Illuminate\Database\DatabaseManager;
use Illuminate\Support\Facades\Config;
use App\VendingMachine\Amount\Manager as AmountManager;

class Manager implements MachineInterface
{
    /**
     * @var ProductManager
     */
    private ProductManager $productManager;
    /**
     * @var PurchaseManager
     */
    private PurchaseManager $purchaseManager;
    /**
     * @var DatabaseManager
     */
    private DatabaseManager $databaseManager;
    /**
     * @var AmountManager
     */
    private AmountManager $amountManager;

    public function __construct(
        ProductManager $productManager,
        PurchaseManager $purchaseManager,
        DatabaseManager $databaseManager,
        AmountManager $amountManager
    ) {
        $this->productManager = $productManager;
        $this->purchaseManager = $purchaseManager;
        $this->databaseManager = $databaseManager;
        $this->amountManager = $amountManager;
    }

    /**
     * @param PurchaseProductDTO $purchaseProductDTO
     * @return Product
     * @throws \Throwable
     */
    public function purchaseProduct(PurchaseProductDTO $purchaseProductDTO): Product
    {
        $product = $purchaseProductDTO->getProduct();
        throw_if($product->isOutOfStock(), new ProductOutOfStockException());
        throw_if($product->price > $purchaseProductDTO->getAmount(), new InSufficientAmountException());

        $amountModel = $this->amountManager->findByType(AmountType::COIN);
        throw_if(
            $amountModel->amount + $purchaseProductDTO->getAmount() > Config::get('vending_machine.max_amount'),
            new MaxAmountExceededException()
        );

        $this->databaseManager->transaction(function () use ($purchaseProductDTO, $product) {
            $this->productManager->deductCount($product);
            $this->purchaseManager->create($purchaseProductDTO);
            $this->amountManager->addAmount($product->price);
        });

        return $product;
    }
}
