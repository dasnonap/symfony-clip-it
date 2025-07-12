<?php

namespace App\Entity;

use App\Interfaces\PaginatableEntityInterface;
use App\Repository\MediaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation\Uploadable;
use Vich\UploaderBundle\Mapping\Annotation\UploadableField;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
#[Uploadable]
class Media implements PaginatableEntityInterface
{
    #[Groups(['post:read'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // #[Groups(['post:read'])]
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    // #[Groups(['post:read'])]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[UploadableField(mapping: 'uploads', fileNameProperty: 'uploadName', size: 'uploadSize')]
    private ?File $uploadFile = null;

    #[ORM\ManyToOne(inversedBy: 'uploadedMedia')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $creator = null;

    // #[Groups(['post:read'])]
    #[ORM\Column]
    public ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Post>
     */
    #[ORM\ManyToMany(targetEntity: Post::class, mappedBy: 'media')]
    private Collection $relatedPosts;

    // #[Groups(['post:read'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $uploadName = null;

    #[ORM\Column(nullable: true)]
    private ?int $uploadSize = null;

    public function __construct()
    {
        $this->relatedPosts = new ArrayCollection();
    }

    public function setUploadFile(?File $file): void
    {
        $this->uploadFile = $file;

        if (!empty($file)) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getUploadFile(): ?File
    {
        return $this->uploadFile;
    }

    public function setUpdatedAt(?\DateTimeImmutable $time): void
    {
        $this->updatedAt = $time;
    }

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): static
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getRelatedPosts(): Collection
    {
        return $this->relatedPosts;
    }

    public function addRelatedPost(Post $relatedPost): static
    {
        if (!$this->relatedPosts->contains($relatedPost)) {
            $this->relatedPosts->add($relatedPost);
            $relatedPost->addMedium($this);
        }

        return $this;
    }

    public function removeRelatedPost(Post $relatedPost): static
    {
        if ($this->relatedPosts->removeElement($relatedPost)) {
            $relatedPost->removeMedium($this);
        }

        return $this;
    }

    public function getUploadName(): ?string
    {
        return $this->uploadName;
    }

    public function setUploadName(string $uploadName): static
    {
        $this->uploadName = $uploadName;

        return $this;
    }

    public function getUploadSize(): ?int
    {
        return $this->uploadSize;
    }

    public function setUploadSize(int $uploadSize): static
    {
        $this->uploadSize = $uploadSize;

        return $this;
    }
}
