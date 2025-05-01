<?php

namespace App\Entity;

use App\Interfaces\EntityValidatorInterface;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
implements
    UserInterface,
    PasswordAuthenticatedUserInterface,
    EntityValidatorInterface
{
    #[Groups(['post:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['post:read'])]
    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your username must be at least {{ limit }} characters long.',
        maxMessage: 'Your username cannot be longer than {{ limit }} characters.',
    )]
    private string $username;

    #[Groups(['post:read'])]
    #[Assert\Email(message: 'Please provide a valid email address.')]
    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your email must be at least {{ limit }} characters long.',
        maxMessage: 'Your email cannot be longer than {{ limit }} characters.',
    )]
    private string $email;

    #[ORM\Column(length: 511)]
    #[Assert\NotBlank]
    private string $password;
    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'user', orphanRemoval: true)]
    private Collection $posts;

    #[Groups(['post:read'])]
    #[Assert\NotBlank]
    #[ORM\Column(type: Types::ARRAY)]
    private array $roles;

    #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
    private ?AccessToken $token = null;

    /**
     * @var Collection<int, Media>
     */
    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'creator', orphanRemoval: true, cascade: ['persist', 'remove'])]
    private Collection $uploadedMedia;

    #[ORM\Column(length: 255)]
    private ?string $refresh_token = null;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->uploadedMedia = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setUser($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getUser() === $this) {
                $post->setUser(null);
            }
        }

        return $this;
    }

    public function eraseCredentials(): void {}

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles = ['ROLE_USER'];
        }

        return $roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function setToken(AccessToken $token): static
    {
        if ($token->getUser() !== $this) {
            $token->setUser($this);
        }
        $this->token = $token;

        return $this;
    }

    public function getToken(): ?AccessToken
    {
        return $this->token;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getUploadedMedia(): Collection
    {
        return $this->uploadedMedia;
    }

    public function addUploadedMedium(Media $uploadedMedium): static
    {
        if (!$this->uploadedMedia->contains($uploadedMedium)) {
            $this->uploadedMedia->add($uploadedMedium);
            $uploadedMedium->setCreator($this);
        }

        return $this;
    }

    public function removeUploadedMedium(Media $uploadedMedium): static
    {
        if ($this->uploadedMedia->removeElement($uploadedMedium)) {
            // set the owning side to null (unless already changed)
            if ($uploadedMedium->getCreator() === $this) {
                $uploadedMedium->setCreator(null);
            }
        }

        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'username' => $this->getUsername(),
            'email' => $this->getEmail(),
            'roles' => $this->getRoles(),
            'token' => $this->getToken()->getToken(),
        ];
    }

    public function getRefreshToken(): ?string
    {
        return $this->refresh_token;
    }

    public function setRefreshToken(string $refresh_token): static
    {
        $this->refresh_token = $refresh_token;

        return $this;
    }
}
