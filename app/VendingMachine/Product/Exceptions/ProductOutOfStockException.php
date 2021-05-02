<?php

namespace App\VendingMachine\Product\Exceptions;

use App\VendingMachine\Core\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class ProductOutOfStockException extends ApiException
{
    public function __construct($message = null, $code = null, Throwable $previous = null)
    {
        parent::__construct($message ?? trans('product.out_of_stock'), $code ?? Response::HTTP_BAD_REQUEST, $previous);
    }
}
