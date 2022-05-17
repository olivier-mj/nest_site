<?php

namespace App\Controller;

use DateTime;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AboutController extends AbstractController
{

    public function __construct(private string $teamFile)
    {
        $this->teamFile = $teamFile;
    }

    #[Route('/a-propos', name: 'page.about')]
    public function index(): Response
    {
        $create = new DateTime('2018-06-28');
        $now = new DateTime('now');
        $age = $now->diff($create)->y;

        $team = Yaml::parseFile($this->teamFile);
        $streamer = $team['team']['streamer'];
        $modorator = $team['team']['modorator'];

        $response = $this->render('about/index.html.twig', [
            'age' => $age,
            'streamers' => $streamer,
            'modorator' => $modorator,
        ]);

        $response->setSharedMaxAge(3600);

        return $response;
    }
}
