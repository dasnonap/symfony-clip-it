<?php

namespace App\Support\Validators;

use App\Interfaces\EntityValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractValidator
{
    public function __construct(
        private ValidatorInterface $validator,
    ) {
    }

    /**
     * Validate function to handle the Entity Validation errors.
     *
     * @param EntityValidatorInterface $entity the entity to check
     */
    public function validate(EntityValidatorInterface $entity): void
    {
        $errors = $this->validator->validate($entity);

        if ($errors->has(0)) {
            $this->handleErrorsEntity($errors);
        }
    }

    /**
     * Specify the error handling.
     *
     * @param ConstraintViolationList $errors the incomming errors
     */
    abstract public function handleErrorsEntity(ConstraintViolationList $errors);
}
