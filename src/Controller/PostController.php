<?php

namespace App\Controller;

use App\Entity\Media;
use App\Services\MediaService;
use App\Services\PostService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{
    function __construct(
        public Security $security,
        public PostService $postService,
        public MediaService $mediaService,
    ) {}

    /**
     * Get posts
     */
    #[Route('/api/posts/', name: 'app_api_posts_listing', methods: ['GET'])]
    function index(Request $request): JsonResponse
    {
        $page = $request->get('page', 1);

        $pagination = $this->postService->paginatePosts($page);


        return $this->json(
            $pagination,
            200,
            [],
            ['groups' => ['post:read']]
        );
    }

    #[Route('/api/posts/create', name: 'app_api_posts_create', methods: ['POST'])]
    function create(Request $request): JsonResponse
    {
        if (empty($request)) {
            return $this->json([
                'message' => 'Post required fields are empty',
                'success' => false,
            ], 401);
        }

        $post = $this->postService->createPost($request);

        if (empty($post)) {
            return $this->json([
                'message' => "Post couldn't be created.",
                'status' => false
            ]);
        }

        // Attach Post media files
        if (! empty($request->files->get('files'))) {
            $files = $this->mediaService->createMedia($post, $request);

            dd($files);
        }

        return $this->json([
            'post' => $post->getId(),
            'success' => true
        ]);
    }
}
