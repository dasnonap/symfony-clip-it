<?php

namespace App\Services;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Support\Validators\EntityValidator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class PostService
{
    function __construct(
        public EntityManagerInterface $entityManager,
        public EntityValidator $entityValidator,
        public Security $security,
        public PostRepository $postRepo,
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

    function fetchPosts(Request $request): ArrayCollection
    {
        // Pagerfanta::createForCurrentPageWithMaxPerPage(new ArrayAdapter([]));
        // dd($this->postRepo);
    }
}
