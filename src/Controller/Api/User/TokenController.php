<?php

namespace App\Controller\Api\User;

use App\Services\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends AbstractController
{
    public function __construct(
        private AuthenticationService $authService,
    ) {
    }

    #[Route('api/user/token/validate', methods: ['POST'])]
    public function index(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data['token']) || !array_key_exists('token', $data)) {
            return $this->json([
                'message' => 'Token is not valid.',
                'success' => false,
            ], Response::HTTP_UNAUTHORIZED);
        }

        $token = $data['token'];
        $user = $this->authService->getUserByToken($token);

        if (empty($user)) {
            return $this->json([
                'message' => 'Token is not valid.',
                'success' => false,
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'result' => true,
            'token' => $token,
            'user' => $user->toArray(),
        ]);
    }
}
