<?php

namespace App\Support\Validators;

use App\Interfaces\EntityValidatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidator
{
    function __construct(
        private ValidatorInterface $validator
    ) {}

    /**
     * Validate function to handle the Entity Validation errors
     * @param EntityValidatorInterface $entity the entity to check
     * @return bool 
     */
    final function validate(EntityValidatorInterface $entity): bool
    {
        $errors = $this->validator->validate($entity);

        if (!empty($errors)) {
            $this->handleErrorsEntity($errors);
        }

        return true;
    }

    /**
     * Specify the error handling
     * @param mixed $errors the incomming errors
     */
    abstract function handleErrorsEntity(mixed $errors);
}
