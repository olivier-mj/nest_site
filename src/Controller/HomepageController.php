<?php

namespace App\Controller;

use App\Service\CallApiService;
use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'page.home')]
    public function index(CallApiService $callApiService): Response
    {
        $create = new DateTime('2018-06-28');
        $now = new DateTime('now');
        $age = $now->diff($create)->y;
        
        $posts = $callApiService->getPosts();

        return $this->render('homepage/index.html.twig', [
            'age' => $age,
            'posts' => $posts
        ]);
    }
}
