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
use Illuminate\Support\Facades\Lang;

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
        throw_if($product->isOutOfStock(), new ProductOutOfStockException(Lang::get('general.out_of_stock')));
        throw_if(
            $product->price > $purchaseProductDTO->getAmount(),
            new InSufficientAmountException(Lang::get('general.insufficient_amount'))
        );

        $amountModel = $this->amountManager->findByType(AmountType::COIN);
        throw_if(
            $amountModel->amount + $purchaseProductDTO->getAmount() > Config::get('vending_machine.max_amount'),
            new MaxAmountExceededException(Lang::get('general.max_amount_exceeded'))
        );

        $this->databaseManager->transaction(function () use ($purchaseProductDTO, $product) {
            $this->productManager->deductCount($product);
            $this->purchaseManager->create($purchaseProductDTO);
            $this->amountManager->addAmount($product->price);
        });

        return $product;
    }

    /**
     * @param Purchase $purchase
     * @return Product
     * @throws \Throwable
     */
    public function refundProduct(Purchase $purchase): Product
    {
        $product = $purchase->product;
        $this->databaseManager->transaction(function () use ($product, $purchase) {
            $this->productManager->addCount($product);
            $this->purchaseManager->delete($purchase);
            $this->amountManager->deductAmount($product->price);
        });

        return $product;
    }
}
