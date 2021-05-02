<?php

namespace Tests\Unit\VendingMachine;

use App\VendingMachine\Amount\Models\Amount;
use App\VendingMachine\Core\ValueObjects\AmountType;
use App\VendingMachine\Machine\DTOs\PurchaseProductDTO;
use App\VendingMachine\Machine\Exceptions\InSufficientAmountException;
use App\VendingMachine\Machine\Exceptions\MaxAmountExceededException;
use App\VendingMachine\Machine\Manager;
use App\VendingMachine\Product\Exceptions\ProductOutOfStockException;
use App\VendingMachine\Product\Models\Product;
use App\VendingMachine\Purchase\Models\Purchase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Mockery as m;
use App\VendingMachine\Product\Manager as ProductManager;
use App\VendingMachine\Purchase\Manager as PurchaseManager;
use App\VendingMachine\Amount\Manager as AmountManager;
use Illuminate\Database\DatabaseManager;

class ManagerTest extends TestCase
{
    private $productManager;
    private $purchaseManager;
    private $databaseManager;
    private $amountManager;
    private $manager;

    public function setUp(): void
    {
        $this->productManager = m::mock(ProductManager::class);
        $this->purchaseManager = m::mock(PurchaseManager::class);
        $this->databaseManager = m::mock(DatabaseManager::class);
        $this->amountManager = m::mock(AmountManager::class);
        $this->manager = new Manager(
            $this->productManager,
            $this->purchaseManager,
            $this->databaseManager,
            $this->amountManager
        );
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function it_should_purchase_product()
    {
        $product = m::mock(Product::class);
        $amountModel = m::mock(Amount::class);
        $purchaseProductDTO = new PurchaseProductDTO($product, 12);

        $product->shouldReceive('isOutOfStock')
            ->andReturn(false);

        Lang::shouldReceive('get')
            ->once()
            ->with('general.out_of_stock')
            ->andReturn('The product is currently out of stock.');

        Config::shouldReceive('get')
            ->once()
            ->with('vending_machine.max_amount')
            ->andReturn(100);

        $product->shouldReceive('getAttribute')
            ->once()
            ->with('price')
            ->andReturn(10);

        Lang::shouldReceive('get')
            ->once()
            ->with('general.insufficient_amount')
            ->andReturn('The amount entered is not sufficient.');

        $this->amountManager->shouldReceive('findByType')
            ->once()
            ->with(AmountType::COIN)
            ->andReturn($amountModel);

        $amountModel->shouldReceive('getAttribute')
            ->once()
            ->with('amount')
            ->andReturn(20);

        Lang::shouldReceive('get')
            ->once()
            ->with('general.max_amount_exceeded')
            ->andReturn('Maximum amount has been exceeded.');

        $this->databaseManager->shouldReceive('transaction')
            ->once()
            ->with(m::type('callable'))
            ->andReturnUsing(function ($callable) {});

        $this->assertInstanceOf(Product::class, $this->manager->purchaseProduct($purchaseProductDTO));
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_out_of_stock()
    {
        $product = m::mock(Product::class);
        $purchaseProductDTO = new PurchaseProductDTO($product, 12);

        $product->shouldReceive('isOutOfStock')
            ->andReturn(true);

        $this->expectException(ProductOutOfStockException::class);

        $this->manager->purchaseProduct($purchaseProductDTO);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function it_should_throw_exception_if_insufficient_amount()
    {
        $product = m::mock(Product::class);
        $purchaseProductDTO = new PurchaseProductDTO($product, 12);

        $product->shouldReceive('isOutOfStock')
            ->andReturn(false);

        Lang::shouldReceive('get')
            ->once()
            ->with('general.out_of_stock')
            ->andReturn('The product is currently out of stock.');

        Config::shouldReceive('get')
            ->once()
            ->with('vending_machine.max_amount')
            ->andReturn(100);

        $product->shouldReceive('getAttribute')
            ->once()
            ->with('price')
            ->andReturn(15);

        $this->expectException(InSufficientAmountException::class);

        $this->manager->purchaseProduct($purchaseProductDTO);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function it_should_throw_exception_if_amount_exceeded()
    {
        $product = m::mock(Product::class);
        $amountModel = m::mock(Amount::class);
        $purchaseProductDTO = new PurchaseProductDTO($product, 12);

        $product->shouldReceive('isOutOfStock')
            ->andReturn(false);

        Lang::shouldReceive('get')
            ->once()
            ->with('general.out_of_stock')
            ->andReturn('The product is currently out of stock.');

        Config::shouldReceive('get')
            ->once()
            ->with('vending_machine.max_amount')
            ->andReturn(100);

        $product->shouldReceive('getAttribute')
            ->once()
            ->with('price')
            ->andReturn(10);

        $this->amountManager->shouldReceive('findByType')
            ->once()
            ->with(AmountType::COIN)
            ->andReturn($amountModel);

        $amountModel->shouldReceive('getAttribute')
            ->once()
            ->with('amount')
            ->andReturn(99);

        $this->expectException(MaxAmountExceededException::class);

        $this->manager->purchaseProduct($purchaseProductDTO);
    }

    /**
     * @test
     *
     * @throws \Throwable
     */
    public function it_should_refund_product()
    {
        $purchase = m::mock(Purchase::class);
        $product = m::mock(Product::class);

        $purchase->shouldReceive('getAttribute')
            ->once()
            ->with('product')
            ->andReturn($product);

        $this->databaseManager->shouldReceive('transaction')
            ->once()
            ->with(m::type('callable'))
            ->andReturnUsing(function () {});

        $this->assertInstanceOf(Product::class, $this->manager->refundProduct($purchase));
    }
}
