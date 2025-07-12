<?php

namespace App\Controller\Api\User;

use App\Services\AuthenticationService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(
        public AuthenticationService $authService,
        public UserService $userService,
    ) {}

    /**
     * Login endpoint.
     */
    #[Route('/api/user/login', name: 'app_api_user_login', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        if (empty($request)) {
            return $this->json([
                'message' => 'User required fields are empty',
                'success' => false,
            ], 401);
        }
        $user = $this->userService->findUser($request);

        $userToken = $this->authService->generateUserToken($user);

        return $this->json([
            'result' => true,
            'token' => $userToken->getToken(),
            'user' => $user->toArray(),
        ]);
    }
}
