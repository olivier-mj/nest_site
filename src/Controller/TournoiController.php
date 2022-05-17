<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TournoiController extends AbstractController
{
    #[Route('/tournoi', name: 'page.tournoi')]
    public function index(): Response
    {
        $response =  $this->render('page/tournoi.html.twig', []);

        $response->setSharedMaxAge(3600);
        
        return $response;
    }
}
