<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TwitchController extends AbstractController
{
    #[Route('/twitch', name: 'app_twitch')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TwitchController.php',
        ]);
    }

    #[Route('/twitch/callback', name: 'app_callback')]
    public function callback(Request $request): Response
    {
        dd($request);
    }
}
