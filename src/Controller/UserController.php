<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    function __construct(
        public EntityManagerInterface $entityManager,
        public UserService $userService
    ) {}

    #[Route('/api/user', name: 'app_store_user', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        if (empty($request)) {
            return $this->json([
                'message' => 'User required fields are empty',
                'success' => false,
            ], 401);
        }

        $user = $this->userService->createUser($request);

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/UserController.php',
        ]);
    }

    #[Route('/api/user', name: 'app_get_user', methods: ['GET'])]
    public function index(): JsonResponse
    {

        return $this->json([
            'message' => 'testr'
        ]);
    }
}
