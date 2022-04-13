<?php

namespace App\Controller;

use DateTime;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{
    #[Route('/about', name: 'page.about')]
    public function index(): Response
    {
        $create = new DateTime('2018-06-28');
        $now = new DateTime('now');
        $age = $now->diff($create)->y;
        return $this->render('about/index.html.twig', [
            'age' => $age,
        ]);
    }
}
