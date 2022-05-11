<?php

namespace App\Twig;

use Exception;
use Twig\Environment;
use Twig\TwigFunction;
use App\Services\Cache;
use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Repository\CommentRepository;
use Twig\Extension\AbstractExtension;
use App\Repository\CategoryRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SidebarExtension extends AbstractExtension
{
    private PostRepository $postRepository;

    private CommentRepository $commentRepository;

    private CategoryRepository $categoryRepository;

    private TagRepository $tagRepository;

    private Environment $twig;

    private Cache $cache;

    public function __construct(
        PostRepository $postRepository,
        CommentRepository $commentRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        Environment $twig,
        Cache $cache,
    ) {
        $this->postRepository = $postRepository;
        $this->commentRepository = $commentRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->twig = $twig;
        $this->cache = $cache;
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sidebar', [$this, 'getSidebar'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     * @return string
     */
    public function getSidebar(): string
    {
        return $this->cache->get('sidebar_comments_categories', function (ItemInterface $item) {
        $item->tag(['comments', 'categories','tags', 'posts']);
        return $this->renderSidebar();
        });
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
      * @return string
      */
    private function renderSidebar(): string
    {
        $blogs = $this->postRepository->findForArchive();
        if (!$blogs) {
            throw  new Exception('Unable to find blog posts');
        }

        foreach ($blogs as $post) {
            $year = $post->getCreatedAt()->format('Y');
            $month = $post->getCreatedAt()->format('M');
            $archives[$year][$month][] = $post;
        }


        return $this->twig->render(
            'includes/sidebar.html.twig',
            [
                'posts' => $this->postRepository->findForSidebar(4),
                'categories' => $this->categoryRepository->findForSidebar(),
                'comments' => $this->commentRepository->findForSidebar(4),
                'tags' => $this->tagRepository->findForSidebar(),
                'archives' => $archives
            ]
        );
    }

    /**
     * @param string $message
     * @param \Throwable|null $previous
     * @return NotFoundHttpException
     */
    protected function createNotFoundException(string $message = 'Not Found', \Throwable $previous = null): NotFoundHttpException
    {
        return new NotFoundHttpException($message, $previous);
    }


}
