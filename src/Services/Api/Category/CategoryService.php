<?php

namespace App\Services\Api\Category;

use App\Dto\CategoryDto;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;

class CategoryService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CategoryRepository $categoryRepository,
    ) {}

    /**
     * @param CategoryDto $dto
     * @return Category
     */
    public function create(CategoryDto $dto): Category
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            $category = new Category();

            $category->setName($dto->name);
            $category->setCreatedAt($dto->createdAt);
            $category->setUser($dto->user);

            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $th) {
            $this->entityManager->rollback();

            throw new \LogicException('Error while saving category');
        }

        return $category;
    }

    /**
     * @param CategoryDto $dto
     * @return Category|null
     */
    public function search(CategoryDto $dto): Category|null
    {
        return $this->categoryRepository->findOneByName($dto);
    }
}
