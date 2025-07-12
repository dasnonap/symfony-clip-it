<?php

namespace App\Controller\Api\User;

use App\Services\AuthenticationService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        public UserService $userService,
        public AuthenticationService $authService,
        public Security $security,
    ) {}

    #[Route('/api/user/register', name: 'app_api_user_register', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        if (empty($request)) {
            return $this->json([
                'message' => 'User required fields are empty',
                'success' => false,
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user = $this->userService->createUser($request);

        if (empty($user)) {
            return $this->json([
                'message' => "User couldn't be created",
                'success' => false,
            ], Response::HTTP_UNAUTHORIZED);
        }

        $userToken = $this->authService->generateUserToken($user);

        return $this->json([
            'result' => true,
            'token' => $userToken->getToken(),
            'user' => $user->toArray(),
        ]);
    }

    #[Route('/api/test', name: 'api_test', methods: ['POST'])]
    public function test(Request $request): JsonResponse
    {
        dd($this->security->getUser());
    }
}
