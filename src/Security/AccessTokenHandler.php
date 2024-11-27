<?php

namespace App\Security;

use App\Exceptions\AuthenticationException;
use App\Repository\AccessTokenRepository;
use App\Services\AuthenticationService;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    function __construct(
        public AccessTokenRepository $tokenRepo,
        public AuthenticationService $authService,
    ) {}

    function getUserBadgeFrom(string $accessToken): UserBadge
    {
        if (empty($accessToken)) {
            throw new AuthenticationException('Not authenticated', 401);
        }

        $accessToken = $this->tokenRepo->findByTokenValue($accessToken);

        if (empty($accessToken->getUser())) {
            throw new AuthenticationException("Token expired", 401);
        }

        if (! $this->authService->isTokenValid($accessToken)) {
            throw new AuthenticationException('Token expired', 401);
        }

        return new UserBadge($accessToken->getUser()->getUserIdentifier());
    }
}
