<?php

namespace App\Controller;


use App\Repository\TagRepository;
use App\Repository\PostRepository;
use App\Controller\AbstractController;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Cache\TagAwareCacheInterface;


class ArchiveController extends AbstractController
{
    private PostRepository $postRepository;

    private CategoryRepository $categoryRepository;

    private TagRepository $tagRepository;



    public function __construct(
        PostRepository $postRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
    ) {
        $this->postRepository = $postRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
    }

    #[Route('/archives', name: 'archive.index')]
    public function index(): Response
    {
        $posts = $this->postRepository->findForArchive(5);
        $categories = $this->categoryRepository->findBy([], ['name' => 'ASC']);
        $tags = $this->tagRepository->findBy([], ['name' => 'ASC']);

        $blogs =  $this->postRepository->findForArchive();

        if (!$blogs) {
            throw $this->createNotFoundException('Unable to find blog posts');
        }

        foreach ($blogs as $post) {
            $year = $post->getCreatedAt()->format('Y');
            $month = $post->getCreatedAt()->format('M');
            $archives[$year][$month][] = $post;
        }

        return $this->render('archive/index.html.twig', [
            'posts' => $posts,
            'categories' => $categories,
            'tags' => $tags,
            'archives' => $archives
        ]);
    }

    #[Route('/archive/{annee}', name: 'archive.showyear', requirements: ['annee' => '[0-9\-]*'], methods: ['GET'])]
    public function showYear(int $annee): Response
    {

        if (empty($annee)) {
            return $this->redirectToRoute('archive.index');
        }
        $posts = $this->postRepository->findByYear($annee);


        return $this->render('archive/year.html.twig', [
            'year' => $annee,
            'posts' => $posts,
        ]);
    }

    #[Route('/archive/{annee}/{mois}', name: 'archive.showmonth', requirements: ['annee' => '[0-9]*', 'mois' => '[0-9]*'], methods: ['GET'])]
    public function showMonth(int $annee, int $mois): Response
    {
        $posts = $this->postRepository->findByYearMonth($annee, $mois);

        return $this->render('archive/month.html.twig', [
            'posts' => $posts,
            'month' => $mois,
            'year' => $annee

        ]);
    }

    #[Route('/archives2', name: 'archive.index2')]
    public function archive(): Response
    {
        $blogs =  $this->postRepository->findForArchive();

        if (!$blogs) {
            throw $this->createNotFoundException('Unable to find blog posts');
        }
        foreach ($blogs as $post) {

            $year = $post->getCreatedAt()->format('Y');
            $month = $post->getCreatedAt()->format('M');
            $archives[$year][$month][] = $post;
        }
        return $this->render('archive/archive.html.twig', [
            'archives' => $archives
        ]);
    }
}
