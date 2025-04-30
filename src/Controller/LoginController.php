<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Services\AuthenticationService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{

    function __construct(
        public AuthenticationService $authService,
        public UserService $userService,
    ) {}

    /**
     * Login endpoint
     */
    #[Route('/api/user/login', name: 'app_api_user_login', methods: ['POST'])]
    function index(Request $request): JsonResponse
    {
        if (empty($request)) {
            return $this->json([
                'message' => 'User required fields are empty',
                'success' => false,
            ], 401);
        }
        $user = $this->userService->findUser($request);

        $userToken = $this->authService->generateUserToken($user);

        $response = new JsonResponse(
            [
                'result' => true,
                'token' => $userToken->getToken(),
                'user' => $user->toArray(),
            ]
        );

        $response->headers->setCookie(
            new Cookie(
                'refresh-token',
                '22222-22222-22222-2222222-222222',
                time() + 60 * 60,
                '/',
                null,
                false,
                true,
                false,
                Cookie::SAMESITE_LAX
            )
        );

        return $response;
    }
}
