<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FeedController extends AbstractController
{
    #[Route('/feed', name: 'feed', defaults: ['_format' => "xml"])]
    public function index(Request $request,PostRepository $posts,): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $response = new Response(
            $this->renderView('feed/index.xml.twig', [
                'posts' => $posts->findForFeed(),
                'hostname' => $hostname
            ]), 200
        );

        $response->headers->set('Content-Type', 'text/xml');
        $response->setSharedMaxAge(600);
        return  $response;
    }
}
