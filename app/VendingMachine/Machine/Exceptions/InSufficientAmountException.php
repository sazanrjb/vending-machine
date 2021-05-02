<?php

namespace App\VendingMachine\Machine\Exceptions;

use App\VendingMachine\Core\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class InSufficientAmountException extends ApiException
{
    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ??  trans('general.insufficient_amount'), $code ?? Response::HTTP_BAD_REQUEST, $previous);
    }
}
