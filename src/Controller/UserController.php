<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    function __construct(
        public UserService $userService
    ) {}

    #[Route('/api/user/register', name: 'app_api_user_register', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        if (empty($request)) {
            return $this->json([
                'message' => 'User required fields are empty',
                'success' => false,
            ], 401);
        }

        $user = $this->userService->createUser($request);

        if (empty($user)) {
            return $this->json([
                'message' => "User couldn't be created",
                'success' => false,
            ], 422);
        }

        return $this->json([
            'result' => true,
        ]);
    }
}
