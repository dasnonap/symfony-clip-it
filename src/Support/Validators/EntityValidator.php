<?php

namespace App\Support\Validators;

use App\Exceptions\ValidationException;
use Symfony\Component\Validator\ConstraintViolationList;

class EntityValidator extends AbstractValidator
{
    public function handleErrorsEntity(ConstraintViolationList $errors)
    {
        if (empty($errors)) {
            return;
        }

        throw new ValidationException($errors);
    }
}
