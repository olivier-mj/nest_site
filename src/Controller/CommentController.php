<?php

namespace App\Controller;

use App\Services\Comment\Disqus\DisqusClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CommentController extends AbstractController
{
    #[Route('/comment', name: 'app_comment', methods: 'GET')]
    public function index(DisqusClient $client): Response
    {

        return $this->json([
            $client->getListPost(),
        ]);
    }

    #[Route('/comment/callback', name: 'app_comment_callback')]
    public function callback(Request $request): Response
    {
        dd($request);
    }
}
