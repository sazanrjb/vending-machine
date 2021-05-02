<?php

namespace App\VendingMachine\Purchase\Models;

use App\VendingMachine\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function associateProduct(Product $product)
    {
        return $this->product()->associate($product);
    }
}
