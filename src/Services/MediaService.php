<?php

namespace App\Services;

use App\Entity\Media;
use App\Entity\Post;
use App\Support\Validators\EntityValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use DateTimeImmutable;

class MediaService
{

    function __construct(
        public EntityValidator $entityValidator,
        public EntityManagerInterface $entityManager,
        public Security $security,
    ) {}

    function createMedia(Post $post, Request $request): Media
    {

        $media = new Media();
        $file = $request->files->get('file');

        $media->setUploadFile($file);
        $user = $this->security->getUser();
        $media->setUpdatedAt(new DateTimeImmutable());
        $media->setCreatedAt(new DateTimeImmutable());
        $media->setType('image');
        $media->setCreator($user);

        $media->addRelatedPost($post);

        $this->entityManager->persist($media);
        $this->entityManager->flush();

        return $media;
    }
}
