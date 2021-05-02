<?php

namespace App\VendingMachine\Core\Exceptions;

use Illuminate\Http\Response;
use Throwable;

class ResourceNotFoundException extends ApiException
{
    protected $message = 'Resource not found.';

    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        parent::__construct($message ?? $this->message, Response::HTTP_NOT_FOUND, $previous);
    }
}
