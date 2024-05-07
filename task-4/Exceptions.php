<?php

namespace App\Exceptions;

use Exception;

class PostCreationException extends Exception
{
    public function __construct(string $message = "Failed to create post", int $code = 400)
    {
        parent::__construct($message, $code);
    }
}

class InvalidParameterException extends Exception
{
    public function __construct(string $message = "Invalid parameters", int $code = 422)
    {
        parent::__construct($message, $code);
    }
}
