<?php

namespace App\Services;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class AuthenticationService
{
    function __construct(
        public AccessTokenRepository $accessTokenRepo,
        public EntityManagerInterface $entityManager,
    ) {}

    function generateUserToken(User $user): AccessToken
    {
        $this->invalidateTokens($user);

        $token = new AccessToken();

        $token->setUser($user);
        $token->setToken(Uuid::uuid4());
        $token->setExpirationDate(
            (new DateTime('now + 30 mins'))
        );

        $user->setAccessToken($token);

        $this->entityManager->persist($token);
        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $token;
    }

    /**
     * Invalidate previous tokens
     * @param User $user 
     * @return void
     */
    private function invalidateTokens(User $user): void
    {
        $oldToken = $user->getAccessToken();

        if (empty($oldToken)) {
            return;
        }

        $this->entityManager->remove($oldToken);
        $this->entityManager->flush();
    }

    /**
     * Check if Token is expired 
     * @param AccessToken $token 
     * @return bool
     */
    function isTokenValid(AccessToken $token): bool
    {
        if (empty($token->getUser())) {
            return false;
        }

        return $token->getExpirationDate() > (new DateTime());
    }
}
