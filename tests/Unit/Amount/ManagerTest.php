<?php

namespace Tests\Unit\Amount;

use App\VendingMachine\Amount\Manager;
use App\VendingMachine\Amount\Models\Amount;
use App\VendingMachine\Core\ValueObjects\AmountType;
use Illuminate\Support\Facades\Lang;
use Tests\TestCase;
use Mockery as m;

class ManagerTest extends TestCase
{
    /**
     * @var Amount|
     */
    private Amount $amount;
    /**
     * @var Manager
     */
    private Manager $manager;

    public function setUp(): void
    {
        $this->amount = m::mock(Amount::class);
        $this->manager = new Manager(
            $this->amount
        );
    }

    /**
     * @test
     */
    public function it_should_find_amount_by_type()
    {
        $this->amount->shouldReceive('newQuery->where->first')
            ->once()
            ->withNoArgs()
            ->andReturn(m::mock(Amount::class));

        $this->assertInstanceOf(Amount::class, $this->manager->findByType(AmountType::COIN));
    }

    /**
     * @test
     */
    public function it_should_add_amount()
    {
        $amount = m::mock(Amount::class);
        $manager = m::mock(Manager::class.'[findByType,createOrUpdate]', [
            $this->amount
        ]);

        $manager->shouldReceive('findByType')
            ->once()
            ->with(AmountType::COIN)
            ->andReturn($amount);

        $manager->shouldReceive('createOrUpdate')
            ->once()
            ->with(10, AmountType::COIN, $amount)
            ->andReturn($amount);

        $this->assertInstanceOf(Amount::class, $manager->addAmount(10));
    }

    /**
     * @test
     */
    public function it_should_deduct_amount()
    {
        $amountModel = m::mock(Amount::class);
        $amount = 10;
        $manager = m::mock(Manager::class.'[findByType]', [
            $this->amount
        ]);

        $manager->shouldReceive('findByType')
            ->once()
            ->with(AmountType::COIN)
            ->andReturn($amountModel);

        Lang::shouldReceive('get')
            ->once()
            ->with('general.not_found', [
                'Entity' => 'Amount'
            ])
            ->andReturn('Amount not found.');

        $amountModel->shouldReceive('getAttribute')
            ->once()
            ->with('amount')
            ->andReturn(20);

        $amountModel->shouldReceive('forceFill')
            ->once()
            ->with([
                'amount' => 20 - $amount,
            ])
            ->andReturnSelf();

        $amountModel->shouldReceive('save')
            ->once()
            ->withNoArgs()
            ->andReturnTrue();

        $this->assertInstanceOf(Amount::class, $manager->deductAmount($amount));
    }
}
