<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Post;
use App\Entity\Comment;
use App\Services\Cache;
use App\Entity\Category;
use App\Form\CommentType;
use App\Repository\PostRepository;
use App\Form\CommentUserOfflineType;
use App\Controller\AbstractController;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class BlogController extends AbstractController
{
    private PostRepository $repository;
    private EntityManagerInterface $entityManager;
    private Cache $cache;

    public function __construct(
        PostRepository $repository,
        EntityManagerInterface $entityManager,
        Cache $cache
    ) {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->cache = $cache;
    }

    #[Route('/blog', name: 'blog.index')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $this->repository->findForBlog(),
            $request->query->getInt('page', 1),
            9
        );

        $response = $this->render('blog/index.html.twig', [
            'pagination' => $pagination
        ]);

        $response->setSharedMaxAge(600);
        return $response;
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     */
    #[Route('/blog/{slug}-{id}', name: 'blog.show', requirements: ['slug' => '[a-z0-9\-]*', 'id' => '[0-9\-]*'], methods: ['GET', 'POST'])]
    public function show(string $slug, Post $post, Request $request): Response
    {
        if ($post->getSlug() !== $slug) {
            return  $this->redirectToRoute(
                'blog.show',
                [
                    'id' => $post->getId(),
                    'slug' => $post->getSlug()
                ]
            );
        }
        $comment = new Comment();

        if (!$this->isGranted('IS_AUTHENTICATED_ANONYMOUSLY')) {
            $form = $this->createForm(CommentType::class, $comment);
        } else {
            $form = $this->createForm(CommentUserOfflineType::class, $comment);
        }


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ip = $this->get_ip_address();

            if ($this->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user = $this->getUserOrThrow();
                $comment->setUser($user);
            }
            $comment->setPost($post);
            $comment->setIpAddress($ip);


            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $this->cache->invalidateTags(['comments', 'sidebar_comment', 'tagssidebar_comments_categories']);
            $this->cache->delete('comments');
            $this->cache->delete('sidebar_comment');
            $this->cache->delete('tagssidebar_comments_categories');

            return $this->redirectToRoute('blog.show', ['id' => $post->getId(), 'slug' =>  $post->getSlug()]);
        }

        $response = $this->render('blog/show.html.twig', [
            'slug' => $slug,
            'post' => $post,
            'form' => $form->createView(),
        ]);

        $response->setSharedMaxAge(600);
        return $response;
    }



    #[Route('blog/categories', name: 'categories.list')]
    public function categories(CategoryRepository $categories): Response
    {
        $response = $this->render('blog/categories.html.twig', [
            'categories' => $categories->findAll()
        ]);
        $response->setSharedMaxAge(600);
        return $response;

    }

    #[Route('blog/category/{slug}-{id<\d+>}', name: 'categories.show', requirements: ['slug' => '[a-z0-9\-]*', 'id' => '[0-9\-]*'])]
    public function category(PaginatorInterface $paginator, string $slug, int $id, Category $category, Request $request): Response
    {
        if ($category->getSlug() !== $slug) {
            return  $this->redirectToRoute(
                'categories.show',
                [
                    'slug' => $category->getSlug(),
                    'id' => $category->getId(),
                ]
            );
        }

        $pagination = $paginator->paginate(
            $this->repository->findPostByCategory((int)$category->getId()),
            $request->query->getInt('page', 1),
            9
        );
        $response = $this->render('blog/category.html.twig', [
            'category' => $category,
            'pagination' => $pagination,
            'slug' => $slug
        ]);

        $response->setSharedMaxAge(600);
        
        return $response;
    }

    #[Route('blog/tag/{slug}-{id}', name: 'tag.show', requirements: ['slug' => '[a-z0-9\-]*', 'id' => '[0-9\-]*'])]
    public function tag(PaginatorInterface $paginator, Tag $tag, string $slug, Request $request): Response
    {
        if ($tag->getSlug() !== $slug) {
            return  $this->redirectToRoute(
                'tag.show',
                [
                    'id' => $tag->getId(),
                    'slug' => $tag->getSlug()
                ]
            );
        }

        $pagination = $paginator->paginate(
            $this->repository->findPostByTag($tag->getId()),
            $request->query->getInt('page', 1),
            9
        );

        $response = $this->render('blog/tag.html.twig', [
            'tag' => $tag,
            'pagination' => $pagination
        ]);
        $response->setSharedMaxAge(600);
        
        return $response;
    }

    private function get_ip_address(): string
    {
        if (isset($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // Proxy servers can send through this header like this: X-Forwarded-For: client1, proxy1, proxy2
            // Make sure we always only send through the first IP in the list which should always be the client IP.
            return (string) self::is_ip_address(trim(current(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']))));
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return '';
    }

    private static function is_ip_address(string $ip): string
    {
        return $ip;
    }
}
