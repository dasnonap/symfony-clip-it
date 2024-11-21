<?php

namespace App\Exceptions;

// Use this interface to display the message exception to the user
interface MessageException
{
    /**
     * Specify how the Exception will handle the error list
     */
    public function getErrorList();
}
