<?php

namespace App\Exceptions;

use Exception;
use LogicException;
use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends Exception implements MessageException
{
    function __construct(
        private ConstraintViolationList $errorList
    ) {
        parent::__construct();
    }

    /**
     * Get the Parsed Error List
     * @return array returns the error list or throws LogicException if no errors
     */
    function getErrorList(): array
    {
        $errorMessages = [];

        if (empty($this->errorList)) {
            throw new LogicException("Exception is thrown with empty errors");
        }

        foreach ($this->errorList as $error) {
            dd($error);
        }

        return $errorMessages;
    }
}
