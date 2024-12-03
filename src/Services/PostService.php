<?php

namespace App\Services;

use App\Entity\Post;
use App\Support\Validators\EntityValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class PostService
{
    function __construct(
        public EntityManagerInterface $entityManager,
        public EntityValidator $entityValidator,
        public Security $security,
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
}
