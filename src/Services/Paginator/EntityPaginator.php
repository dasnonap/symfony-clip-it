<?php

namespace App\Services\Paginator;

use App\Interfaces\PaginatableEntityInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\SerializerInterface;

class EntityPaginator
{
    private QueryBuilder $query;

    function __construct(
        private readonly RequestStack $requestStack,
        private readonly EntityManagerInterface $entityManager,
        private readonly SerializerInterface $serializer,
    ) {}

    /**
     * Create pagination
     * @param PaginatableEntityInterface $entity
     * @param int $page,
     * @param int $postPerPage
     * @return array
     */
    function paginate(PaginatableEntityInterface $entity, int $page, int $postPerPage): array
    {
        $offset = $page === 1 ? 0 : ($page * $postPerPage) - $postPerPage;

        $this->query = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from($entity::class, 'p')
            ->setFirstResult($offset)
            ->setMaxResults($postPerPage);

        $items = $this->query->getQuery()->getResult();
        $paginator = new Paginator($this->query, true);
        $maxItems = count($paginator);
        $maxPages = ceil($maxItems / $postPerPage);
        // dd($items);
        // dd($this->serializer->serialize($items[0], 'json'));dd
        return [
            'page' => $page,
            'posts_per_page' => $postPerPage,
            'items' => $items,
            'max_num_pages' => $maxPages,
            'has_next_page' => $page < $maxPages
        ];
    }
}
