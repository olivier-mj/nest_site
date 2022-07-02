<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Services\Search\SearchInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\Event\Subscriber\Paginate\Callback\CallbackPagination;

class SearchController extends AbstractController
{
    public function __construct(
        private PaginatorInterface $paginator,
        private PostRepository $posts
    ) {
        $this->paginator = $paginator;
        $this->posts = $posts;
    }


    #[Route('/search', name: 'page.search', methods: ['GET'])]
    public function search(Request $request, SearchInterface $search): Response
    {
        $q = trim($request->get('q', ''));

        $page = $request->query->getInt('page', 1);
        $results = $search->search($q, [], 10, $page);
        $paginableResults = new CallbackPagination(fn () => $results->getTotal(), fn () => $results->getItems());

        return $this->render('page/search.html.twig', [
            'q' => $q,
            'total' => $results->getTotal(),
            'results' => $this->paginator->paginate($paginableResults, $page),
            'randposts' => $this->posts->findRandom()
        ]);
    }
}
