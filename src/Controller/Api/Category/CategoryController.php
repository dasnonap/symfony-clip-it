<?php

namespace App\Controller\Api\Category;

use App\Dto\CategoryDto;
use App\Services\Api\Category\CategoryService;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryService $categoryService,
        private readonly Security $security,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {}

    #[Route('/list', name: 'api_categories_list', methods: ['GET'])]
    public function index(Request $request): JsonResponse
    {
        throw new \Exception("not supported yet");
    }

    #[Route('/create', name: 'app_categories_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $rawContent = $request->getContent();

        if (empty($rawContent)) {
            return $this->json([
                'message' => 'Category required fields are required.',
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }

        $requestBody = json_decode($rawContent, true);
        $dto = $this->serializer->deserialize(
            $request->getContent(),
            CategoryDto::class,
            'json'
        );

        $dto->createdAt = $requestBody['createdAt'] ?? new DateTimeImmutable('now');
        $dto->user = $this->security->getUser();

        $errorMessages = [];
        $validation = $this->validator->validate($dto);

        // Input validation
        if ($validation->count() > 0) {
            foreach ($validation as $violation) {
                $errorMessages[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return $this->json([
                'message' => 'There was an error while completing the operation!',
                'status' => false,
                'errors' => $errorMessages,
            ], Response::HTTP_BAD_REQUEST);
        }

        // Duplicate validation
        if ($this->categoryService->search($dto)) {
            return $this->json([
                'message' => 'Category with this name already exists!',
                'status' => false,
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $category = $this->categoryService->create($dto);
        } catch (\Throwable $th) {
            return $this->json([
                'message' => $th->getMessage(),
                'status' => false
            ]);
        }

        return $this->json([
            'status' => true,
        ], Response::HTTP_OK);
    }
}
