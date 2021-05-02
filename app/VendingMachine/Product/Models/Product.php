<?php

namespace App\VendingMachine\Product\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';

    public function isOutOfStock(): bool
    {
        return $this->total === 0;
    }
}
