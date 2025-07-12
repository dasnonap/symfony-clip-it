<?php

namespace App\Services;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;

class AuthenticationService
{
    public function __construct(
        public AccessTokenRepository $accessTokenRepo,
        public EntityManagerInterface $entityManager,
    ) {}

    /**
     * Generate User Token
     */
    public function generateUserToken(User $user): AccessToken
    {
        $this->invalidateTokens($user);

        $token = new AccessToken();

        $token->setUser($user);
        $token->setToken(Uuid::uuid4());
        $token->setExpirationDate(
            new \DateTime('now + 30 mins')
        );

        $user->setToken($token);

        $this->entityManager->persist($token);
        $this->entityManager->persist($user);

        $this->entityManager->flush();

        return $token;
    }

    /**
     * Search User by raw access token
     */
    public function getUserByToken(string $token): User|null
    {
        $accessTokenRepo = $this->entityManager->getRepository(AccessToken::class);
        $now = time();

        $accessToken = $accessTokenRepo->findOneBy([
            'token' => $token,
        ]);

        if (empty($accessToken)) {
            return null;
        }

        // Do not invalidate token just return null / prevents spamming and invalidating tokens 
        if ($now > $accessToken->getExpirationDate()->getTimestamp()) {
            return null;
        }

        return $accessToken->getUser();
    }

    /**
     * Invalidate previous tokens.
     */
    private function invalidateTokens(User $user): void
    {
        $oldToken = $user->getToken();

        if (empty($oldToken)) {
            return;
        }

        $this->entityManager->remove($oldToken);
        $this->entityManager->flush();
    }
}
