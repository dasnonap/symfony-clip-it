<?php

namespace App\Services;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class AuthenticationService
{
    public function __construct(
        public AccessTokenRepository $accessTokenRepo,
        public EntityManagerInterface $entityManager,
        protected readonly MailerInterface $mailer,
    ) {}

    /**
     * Generate User Token.
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
     * Search User by raw access token.
     */
    public function getUserByToken(string $token): ?User
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
     * Send User OTP email
     */
    public function sendUserOtp(User $user): bool
    {
        $token = $user->getToken();

        $email = (new Email())
            ->from('aaaaa@test.com')
            ->to($user->getEmail())
            ->subject('This is a test OTP Email')
            ->text('yupeeeee');

        try {
            $this->mailer->send($email);
        } catch (\Throwable $th) {
            //throw $th;

            return false;
        }

        return true;
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
