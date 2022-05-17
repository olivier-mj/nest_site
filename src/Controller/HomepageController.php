<?php

namespace App\Controller;

use DateTime;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'page.home')]
    public function index(PostRepository $posts): Response
    {
        $create = new DateTime('2018-06-28');
        $now = new DateTime('now');
        $age = $now->diff($create)->y;
        


        $response = $this->render('page/homepage.html.twig', [
            'age' => $age,
            'posts' => $posts->findForHomepage(4),
        ]);

        $response->setSharedMaxAge(3600);
        
        return $response;
    }
}
