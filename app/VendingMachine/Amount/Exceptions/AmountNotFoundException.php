<?php

namespace App\VendingMachine\Amount\Exceptions;

use App\VendingMachine\Core\Exceptions\ApiException;
use Illuminate\Http\Response;
use Throwable;

class AmountNotFoundException extends ApiException
{
    protected $message = 'Amount not found.';

    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?? $this->message, Response::HTTP_BAD_REQUEST, $previous);
    }
}
