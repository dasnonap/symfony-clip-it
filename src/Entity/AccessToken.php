<?php

namespace App\Entity;

use App\Repository\AccessTokenRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessTokenRepository::class)]
class AccessToken
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 511)]
    private ?string $token = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $expiration_date = null;

    #[ORM\OneToOne(cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToOne(mappedBy: 'token', cascade: ['persist', 'remove'])]
    private ?OtpCode $otpCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(\DateTimeInterface $expiration_date): static
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user_id): static
    {
        $this->user = $user_id;

        return $this;
    }

    public function isTokenValid(): bool
    {
        $now = new \DateTime();

        return ($this->getExpirationDate() > $now)
            && !empty($this->user);
    }

    public function getOtpCode(): ?OtpCode
    {
        return $this->otpCode;
    }

    public function setOtpCode(OtpCode $otpCode): static
    {
        // set the owning side of the relation if necessary
        if ($otpCode->getToken() !== $this) {
            $otpCode->setToken($this);
        }

        $this->otpCode = $otpCode;

        return $this;
    }
}
