<?php

namespace Tests\Unit\Purchase;

use App\VendingMachine\Core\Exceptions\ResourceNotFoundException;
use App\VendingMachine\Machine\DTOs\PurchaseProductDTO;
use App\VendingMachine\Product\Models\Product;
use App\VendingMachine\Purchase\Manager;
use App\VendingMachine\Purchase\Models\Purchase;
use Illuminate\Support\Facades\Lang;
use Mockery as m;
use Tests\TestCase;

class ManagerTest extends TestCase
{
    /**
     * @var Purchase
     */
    private Purchase $purchase;
    /**
     * @var Manager
     */
    private Manager $manager;

    public function setUp(): void
    {
        $this->purchase = m::mock(Purchase::class);
        $this->manager = new Manager(
            $this->purchase
        );
    }

    /**
     * @test
     */
    public function it_should_find_purchase()
    {
        $this->purchase->shouldReceive('newQuery')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->purchase->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(m::mock(Purchase::class));

        Lang::shouldReceive('get')
            ->once()
            ->with('not_found', [
                'Entity' => 'Purchase information'
            ])
            ->andReturn('Purchase information not found.');

        $this->assertInstanceOf(Purchase::class, $this->manager->find(1));
    }

    /**
     * @test
     */
    public function test_it_should_throw_exception_if_purchase_not_found()
    {
        $this->purchase->shouldReceive('newQuery')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->purchase->shouldReceive('find')
            ->once()
            ->with(1)
            ->andReturn(null);

        $this->expectException(ResourceNotFoundException::class);

        $this->assertInstanceOf(Purchase::class, $this->manager->find(1));
    }

    /**
     * @test
     */
    public function it_should_create_purchase()
    {
        $product = m::mock(Product::class);
        $price = 10;
        $purchaseProductDTO = new PurchaseProductDTO($product, 20);

        $this->purchase->shouldReceive('associateProduct')
            ->once()
            ->with($product)
            ->andReturnSelf();

        $product->shouldReceive('getAttribute')
            ->twice()
            ->with('price')
            ->andReturn($price);

        $this->purchase->shouldReceive('forceFill')
            ->once()
            ->with([
                'price' => 10,
                'amount_paid' => $purchaseProductDTO->getAmount(),
                'amount_returned' => $purchaseProductDTO->getAmount() - $price
            ])
            ->andReturnSelf();

        $this->purchase->shouldReceive('save')
            ->once()
            ->withNoArgs()
            ->andReturnTrue();

        $this->assertInstanceOf(Purchase::class, $this->manager->create($purchaseProductDTO));
    }

    /**
     * @test
     */
    public function it_should_delete_purchase()
    {
        $purchase = m::mock(Purchase::class);
        $purchase->shouldReceive('delete')
            ->once()
            ->withNoArgs()
            ->andReturnTrue();
        $this->manager->delete($purchase);
    }
}
