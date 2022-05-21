<?php

namespace App\Controller;

use App\Services\Twitch\TwitchClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LiveController extends AbstractController
{
    protected TwitchClient $client;

    public function __construct(TwitchClient $client)
    {
        $this->client = $client;
    }

    #[Route('/live', name: 'page.live')]
    public function index(): Response
    {

        $replay  = $this->client->getReplay();

        $response = $this->render('page/live.html.twig', [
            'data' => $replay['data'],
            'pagination' => $replay['pagination']
        ]);

        return $response;
    }
}
