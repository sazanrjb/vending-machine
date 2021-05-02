<?php

namespace App\VendingMachine\Amount;

use App\VendingMachine\Amount\Exceptions\AmountNotFoundException;
use App\VendingMachine\Amount\Models\Amount;
use App\VendingMachine\Core\ValueObjects\AmountType;
use Illuminate\Support\Facades\Lang;

class Manager
{
    /**
     * @var Amount
     */
    private Amount $amount;

    public function __construct(Amount $amount)
    {
        $this->amount = $amount;
    }

    public function findByType(int $type): ?Amount
    {
        return $this->amount->newQuery()
            ->where('type', $type)
            ->first();
    }

    public function addAmount(float $amount, int $amountType = AmountType::COIN): Amount
    {
        $amountModel = $this->findByType($amountType);

        return $this->createOrUpdate($amount, $amountType, $amountModel);
    }

    public function createOrUpdate(float $amount, int $amountType, Amount $amountModel = null)
    {
        $amountModel = $amountModel ?? $this->amount->newInstance();

        $amountModel->forceFill([
            'amount' => $amountModel->amount + $amount,
            'type' => $amountType
        ])->save();

        return $amountModel;
    }

    public function deductAmount(float $amount, int $amountType = AmountType::COIN): Amount
    {
        $amountModel = $this->findByType($amountType);

        throw_if(!$amountModel, new AmountNotFoundException(
            Lang::get('general.not_found', [
                'Entity' => 'Amount'
            ])
        ));

        $amountModel->forceFill([
            'amount' => $amountModel->amount - $amount,
        ])->save();

        return $amountModel;
    }
}
