<?php

namespace App\Controller;

use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'page.blog_index')]
    public function index(CallApiService $callApiService): Response
    {
        $posts = $callApiService->getPosts();
        
        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/blog', name: 'page.blog_show')]
    public function show(): Response
    {
        return $this->render('blog/show.html.twig', [
            'controller_name' => 'BlogController',
        ]);
    }
}
