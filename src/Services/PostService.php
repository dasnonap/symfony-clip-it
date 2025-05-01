<?php

namespace App\Services;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Services\Paginator\EntityPaginator;
use App\Support\Validators\EntityValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class PostService
{
    const POST_PER_PAGE = 12;

    function __construct(
        public EntityManagerInterface $entityManager,
        public EntityValidator $entityValidator,
        public Security $security,
        public PostRepository $postRepo,
        private readonly EntityPaginator $paginator,
    ) {}

    /**
     * Create Post from Request action
     * @param Request $request the incomming request
     * @return Post the created Post
     */
    function createPost(Request $request): Post
    {
        $post = new Post();
        $post->setTitle($request->get('title'));
        $post->setUser($this->security->getUser());
        $this->entityValidator->validate($post);

        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $post;
    }

    /**
     * Create a pagination for the Posts page
     * @param int $page,
     * @return array 
     */
    function paginatePosts(int $page): array
    {
        return $this->paginator->paginate(new Post(), $page, self::POST_PER_PAGE);
    }
}
