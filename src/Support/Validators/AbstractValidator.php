<?php

namespace App\Support\Validators;

use App\Interfaces\EntityValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidator
{
    function __construct(
        private ValidatorInterface $validator
    ) {}

    /**
     * Validate function to handle the Entity Validation errors
     * @param EntityValidatorInterface $entity the entity to check
     * @return void 
     */
    function validate(EntityValidatorInterface $entity): void
    {
        $errors = $this->validator->validate($entity);

        if (!empty($errors)) {
            $this->handleErrorsEntity($errors);
        }
    }

    /**
     * Specify the error handling
     * @param ConstraintViolationList $errors the incomming errors
     */
    abstract function handleErrorsEntity(ConstraintViolationList  $errors);
}
