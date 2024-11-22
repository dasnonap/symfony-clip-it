<?php

namespace App\Services;

use App\Entity\User;
use App\Exceptions\AuthenticationException;
use App\Repository\UserRepository;
use App\Support\Validators\EntityValidator;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    function __construct(
        private UserPasswordHasherInterface $hasher,
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepo,
        private EntityValidator $entityValidator,
    ) {}

    /**
     * Create User action
     * @param Request $request the Incomming Request
     * @param User the inserted user
     */
    function createUser(Request $request): User
    {
        $request = json_decode($request->getContent());

        $user = new User();

        $user->setUsername($request->username);
        $user->setEmail($request->email);
        $user->setPassword(
            $this->hasher->hashPassword($user, $request->password)
        );
        $user->setRoles(['ROLE_USER']);

        $this->entityValidator->validate($user);

        if ($this->checkIfUserExists($user)) {
            throw new AuthenticationException("User already exists with the provided email or username.");
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * Check if User is existing 
     * @param User $user
     * @return bool
     */
    private function checkIfUserExists(User $user): bool
    {
        return ! empty($this->userRepo->findUserByUniqueCredentials(
            $user->getUsername(),
            $user->getEmail()
        ));
    }
}
