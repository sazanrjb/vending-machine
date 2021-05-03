<?php

namespace App\VendingMachine\Product\Models;

use App\VendingMachine\Purchase\Models\Purchase;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function isOutOfStock(): bool
    {
        return $this->total === 0;
    }
}
