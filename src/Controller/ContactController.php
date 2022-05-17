<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'page.contact')]
    public function index(): Response
    {
        $response = $this->render('page/contact.html.twig', [
 
        ]);

        $response->setSharedMaxAge(3600);
        
        return $response;
    }
}
