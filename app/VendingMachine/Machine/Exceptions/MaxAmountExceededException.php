<?php

namespace App\VendingMachine\Machine\Exceptions;

use App\VendingMachine\Core\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class MaxAmountExceededException extends ApiException
{
    public function __construct($message = null, $code = null, Throwable $previous = null)
    {
        parent::__construct($message ?? trans('general.max_amount_exceeded'), Response::HTTP_BAD_REQUEST, $previous);
    }
}
