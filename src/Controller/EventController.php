<?php

namespace App\Controller;


use App\Controller\AbstractController;
use App\Repository\EventRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{

    public function __construct(private EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository; 
    }

    #[Route('/events', name: 'page.event')]
    public function index(): Response
    {
        $events = $this->eventRepository->findForHomepage();
        
        return $this->render('events/index.html.twig', [
            'events' =>  $events
        ]);
    }
}
