<?php

namespace App\Exceptions;

use Exception;

class AuthenticationException extends Exception implements MessageException
{
    function __construct(string $message)
    {
        parent::__construct(code: 401);
        $this->message = $message;
    }

    function getErrorList()
    {
        return [
            'type' => 'authentication',
            'message' => $this->message,
        ];
    }
}
