<?php

namespace App\Services\User;

use App\Entity\User;
use App\Security\Algorithms\SHA;
use App\Support\Enums\TokenType;

class TokenService
{
    const TYPE_ACCESS = 'access';
    const TYPE_REFRESH = 'refresh';
    private SHA $hashingAlgorithm;

    function __construct()
    {
        $this->hashingAlgorithm = new SHA();
    }

    function generateToken(TokenType $type, User $user)
    {
        $uniqueToken = $this->hashingAlgorithm->hash($user->getId());

        dd($uniqueToken);
    }
}
