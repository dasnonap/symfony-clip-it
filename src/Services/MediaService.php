<?php

namespace App\Services;

use App\Entity\Media;
use App\Entity\Post;
use App\Support\Validators\EntityValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use LogicException;

class MediaService
{

    function __construct(
        public EntityValidator $entityValidator,
        public EntityManagerInterface $entityManager,
        public Security $security,
    ) {}

    /**
     * Create Media entry when adding post
     * @param Post $post the post
     * @param Request $request the current request
     * @return ArrayCollection of created files
     */
    function createMedia(Post $post, Request $request): ArrayCollection
    {
        $files = $request->files->get('files');

        if (empty($files)) {
            throw new LogicException("Files not provided.");
        }

        $uploadedMedia = new ArrayCollection();

        foreach ($files as $file) {
            $media = new Media();

            $media->setUploadFile($file);
            $user = $this->security->getUser();
            $media->setUpdatedAt(new DateTimeImmutable());
            $media->setCreatedAt(new DateTimeImmutable());
            $media->setType('image');
            $media->setCreator($user);

            $media->addRelatedPost($post);

            $this->entityManager->persist($media);
            $this->entityManager->flush();

            $uploadedMedia->add($media);
        }

        return $uploadedMedia;
    }
}
