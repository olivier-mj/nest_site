<?php

namespace App\Controller;

use App\Services\Twitch\TwitchClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LiveController extends AbstractController
{
    protected TwitchClient $client;

    public function __construct(TwitchClient $client)
    {
        $this->client = $client;
    }

    #[Route('/live', name: 'page.live', methods: 'GET')]
    public function getLive(): Response
    {

        $replay  = $this->client->getReplay();

        $response = $this->render('page/live.html.twig', [
            'data' => $replay['data'],
            'pagination' => $replay['pagination']
        ]);

        // dump($replay['data']);
        return $response;
    }

    #[Route('/live/stream', name: 'page.stream')]
    public function getStreamTeam(): JsonResponse
    {
        $response = new JsonResponse(
            $this->client->getLive([
                // '150551018',
                // '109773820',
                // '429055487',
                // '93012767',
                // '100949335',
                // '135565655',
                // '49869023',
                // '622498423'
            ])
        );

        // $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );

        // $response->setSharedMaxAge(120);

        return $response;
    }
}
