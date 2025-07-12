<?php

namespace App\Exceptions;

class AuthenticationException extends \Exception implements MessageException
{
    public function __construct(string $message)
    {
        parent::__construct(code: 401);
        $this->message = $message;
    }

    public function getErrorList()
    {
        return [
            'type' => 'authentication',
            'message' => $this->message,
        ];
    }
}
