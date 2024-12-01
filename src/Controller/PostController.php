<?php

namespace App\Controller;

use App\Entity\Media;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    function __construct(
        public Security $security
    ) {}

    #[Route('/api/posts/', name: 'app_api_posts_listing', methods: ['GET'])]
    function index()
    {
        throw new Exception('not implemented yet');
    }

    #[Route('/api/posts/create', name: 'app_api_posts_create', methods: ['POST'])]
    function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $media = new Media();
        $file = $request->files->get('file');

        $media->setUploadFile($file);
        $user = $this->security->getUser();
        $media->setUpdatedAt(new DateTimeImmutable());
        $media->setCreatedAt(new DateTimeImmutable());
        $media->setType('image');
        $media->setCreator($user);

        // Persist the entity
        $entityManager->persist($media);
        $entityManager->flush();
        dd($media);
    }
}
