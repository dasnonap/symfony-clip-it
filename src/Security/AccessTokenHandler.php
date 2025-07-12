<?php

namespace App\Security;

use App\Exceptions\AuthenticationException;
use App\Repository\AccessTokenRepository;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function __construct(
        public AccessTokenRepository $tokenRepo,
    ) {
    }

    public function getUserBadgeFrom(string $token): UserBadge
    {
        if (empty($token)) {
            throw new AuthenticationException('Not authenticated', 401);
        }

        $accessToken = $this->tokenRepo->findByToken($token);

        if (!$accessToken->isTokenValid()) {
            throw new AuthenticationException('Token expired', 401);
        }

        return new UserBadge($accessToken->getUser()->getUserIdentifier());
    }
}
