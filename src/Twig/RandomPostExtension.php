<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Environment;
use Twig\TwigFunction;
use App\Services\Cache;
use App\Repository\PostRepository;
use Twig\Extension\AbstractExtension;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Response;

class RandomPostExtension extends AbstractExtension
{
    public function __construct(
        private PostRepository     $posts,
        private  Environment $twig,
    ) {
        $this->posts = $posts;
        $this->twig = $twig;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getRandomPosts', [$this, 'getRandomPosts'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @throws \Psr\Cache\InvalidArgumentException
     * @param integer|null $limit
     * @return string
     */
    public function getRandomPosts(?int $limit)
    {
        if (null === $limit) {
            $limit = 4;
        }

        return $this->renderPost($limit);
    }

    /**
     * @throws \Twig\Error\SyntaxError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\LoaderError
     * @param integer|null $limit
     * @return string
     */
    private function renderPost(?int $limit): string
    {
        return $this->twig->render('includes/randompost.html.twig', [
            'posts' => $this->posts->findRandom($limit)
        ]);
    }
}
