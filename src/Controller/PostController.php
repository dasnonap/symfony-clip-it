<?php

namespace App\Controller;

use App\Entity\Media;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController
{
    function __construct() {}

    #[Route('/api/posts/', name: 'app_api_posts_listing', methods: ['GET'])]
    function index()
    {
        throw new Exception('not implemented yet');
    }

    #[Route('/api/posts/create', name: 'app_api_posts_create', methods: ['POST'])]
    function create(Request $request): JsonResponse
    {
        // $media = new Media();

        // $form = $this->createForm(Media::class, $media);
        dd($request->request->get('title'));
    }
}
