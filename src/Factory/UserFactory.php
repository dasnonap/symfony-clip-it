<?php

namespace App\Factory;

use App\Entity\User;
use App\Factory\EntityFactoryInterface;

class UserFactory implements EntityFactoryInterface
{
    function __construct() {}

    static function createEntity(): User
    {
        return (new User())
            ->setId(rand(1, 300))
            ->setUsername('username')
            ->setEmail('nobody@example.com')
            ->setPassword('Alabaaala1');
    }
}
