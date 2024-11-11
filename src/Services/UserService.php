<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    function __construct(
        private UserPasswordHasherInterface $hasher,
        private EntityManagerInterface $entityManager,
    ) {}

    function createUser(Request $request)
    {
        $request = json_decode($request->getContent());

        $user = new User();

        $user->setEmail($request->email);
        $user->setPassword($this->hasher->hashPassword($user, $request->password));
        $user->setRoles(['ROLE_USER']);

        dd($user);
    }
}
