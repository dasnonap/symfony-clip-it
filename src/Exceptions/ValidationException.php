<?php

namespace App\Exceptions;

use Exception;

class ValidationException extends Exception implements MessageException
{
    function __construct($message)
    {
        parent::__construct($message, 422);
    }
}
