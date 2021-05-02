<?php

namespace App\VendingMachine\Core\Exceptions;

use Illuminate\Http\Response;
use Throwable;

class ApiException extends \Exception
{
    protected $message = 'Something went wrong';

    public function __construct($message = null, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?? $this->message, $code ?? Response::HTTP_INTERNAL_SERVER_ERROR, $previous);
    }
}
