<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;

class ApiException extends Exception
{
    public function __construct(string $message = "Server Error", int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, $statusCode);
    }
}
