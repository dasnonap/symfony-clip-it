<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    function __construct(
        EntityManagerInterface $entityManager
    ) {}

    #[Route('/api/user', name: 'app_store_user', methods: ['POST'])]
    public function store(Request $request): JsonResponse
    {
        $request = json_decode($request->getContent(), true);

        if (empty($request)) {
            return $this->json([
                'message' => 'User required fields are empty',
                'success' => false,
            ], 401);
        }

        $user = new User();

        // $user->setEmail($request->email);
        // $user->setPassword($request->password);


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
