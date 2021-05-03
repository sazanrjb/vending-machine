<?php

namespace Tests\Unit\Product;

use App\VendingMachine\Core\Exceptions\ResourceNotFoundException;
use App\VendingMachine\Product\Manager;
use App\VendingMachine\Product\Models\Product;
use Illuminate\Support\Facades\Lang;
use Mockery as m;
use Tests\TestCase;

class ManagerTest extends TestCase
{
    /**
     * @var Product|m\LegacyMockInterface|m\MockInterface
     */
    private $product;
    /**
     * @var Manager
     */
    private $manager;

    public function setUp():void
    {
        $this->product = m::mock(Product::class);
        $this->manager = new Manager(
            $this->product
        );
    }

    /**
     * @test
     */
    public function it_should_find_product()
    {
        $product = m::mock(Product::class);
        $this->product->shouldReceive('newQuery->find')
            ->once()
            ->with(1)
            ->andReturn($product);

        Lang::shouldReceive('get')
            ->once()
            ->with('general.not_found', [
                'Entity' => 'Product'
            ])
            ->andReturn('Product not found.');

        $this->assertInstanceOf(Product::class, $this->manager->find(1));
    }

    /**
     * @test
     */
    public function it_should_throw_exception_if_product_not_found()
    {
        $this->product->shouldReceive('newQuery->find')
            ->once()
            ->with(1)
            ->andReturn(null);

        $this->expectException(ResourceNotFoundException::class);

        $this->assertInstanceOf(Product::class, $this->manager->find(1));
    }

    /**
     * @test
     */
    public function it_should_add_product_count()
    {
        $product = m::mock(Product::class);

        $product->shouldReceive('getAttribute')
            ->once()
            ->with('total')
            ->andReturn(10);

        $product->shouldReceive('setAttribute')
            ->once()
            ->with('total', 11)
            ->andReturnSelf();

        $product->shouldReceive('save')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->assertInstanceOf(Product::class, $this->manager->addCount($product));
    }

    /**
     * @test
     */
    public function it_should_deduct_product_count()
    {
        $product = m::mock(Product::class);

        $product->shouldReceive('getAttribute')
            ->once()
            ->with('total')
            ->andReturn(10);

        $product->shouldReceive('setAttribute')
            ->once()
            ->with('total', 9)
            ->andReturnSelf();

        $product->shouldReceive('save')
            ->once()
            ->withNoArgs()
            ->andReturnSelf();

        $this->assertInstanceOf(Product::class, $this->manager->deductCount($product));
    }
}
