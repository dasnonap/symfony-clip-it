<?php

use App\Factory\UserFactory;
use App\Repository\UserRepository;
use App\Services\User\TokenService;
use App\Support\Enums\TokenType;
use PHPUnit\Framework\TestCase;

class TokenGeneration extends TestCase
{
    function testTokenGeneration(): void
    {
        $user = UserFactory::createEntity();
        $tokenService = new TokenService();
        $accessToken = $tokenService->generateToken(TokenType::ACCESS, $user);

        dd($accessToken);
        // $user->setAccessToken($accessToken);
    }
}
