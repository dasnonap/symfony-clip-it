<?php

namespace App\Services\Api\Category;

use App\Dto\CategoryDto;
use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Null_;

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
     * @param CategoryDto $updated
     * @param Category $category
     * @return Category
     */
    public function update(CategoryDto $updated, Category $category): Category
    {
        $this->entityManager->getConnection()->beginTransaction();

        try {
            $category->setName($updated->name ?? $category->getName());
            $category->setCreatedAt($updated->createdAt ?? $category->getCreatedAt());
            $category->setUser($dto->user ?? $category->getUser());

            $this->entityManager->persist($category);
            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (\Throwable $th) {
            $this->entityManager->rollback();

            throw new \LogicException('Error while updating category');
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

    /**
     * @param string $param
     * @param CategoryDto $dto
     * @return Category|null
     */
    public function findCategoryBy(string $param, CategoryDto $dto): Category|null
    {
        return $this->categoryRepository->findOneBy([$param => $dto->$param]);
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function delete(Category $category): bool
    {
        try {
            $this->entityManager->remove($category);
            $this->entityManager->flush();
        } catch (\Throwable $th) {
            throw new \LogicException("Error while deleting category.");
        }

        return true;
    }
}
