<?php

namespace App\Dto;

use DateTimeImmutable;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class CategoryDto
{
    public function __construct(
        public ?string $id,

        #[Assert\Length(
            min: 5,
            max: 255,
            minMessage: 'The name needs to be longer than {{ limit }} characters.',
            maxMessage: 'The name needs cannot be longer than {{ limit }} characters.',
        )]
        public string $name = '',

        #[Assert\NotEmpty()]
        public ?DateTimeImmutable $createdAt = null,

        #[Assert\NotEmpty()]
        public ?User $user = null,
    ) {}

    #[Assert\Callback]
    public function validate(ExecutionContextInterface $context): void
    {
        if (!in_array('ROLE_USER', $this->user->getRoles())) {
            $context->buildViolation("User doesn't have rights for the operation")
                ->atPath('user')
                ->addViolation();
        }
    }
}
