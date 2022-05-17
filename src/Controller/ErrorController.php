<?php

namespace App\Controller;

use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class ErrorController extends AbstractController
{
    private PostRepository $postRepository;

    public function __construct(PostRepository $postRepository  ) {
        $this->postRepository = $postRepository;
    }
    
    #[Route('/error', name: 'app_error')]
    public function show(Request $request): Response
    {
        
        $code = $request->attributes->get('exception')->getCode();
        if ( $code <=  0) {
            $msg = 'Oups !! Page introuvable !!';
            $statusCode = '404';
        } else {
            $msg = 'Oups !! '.$request->attributes->get('exception')->getMessage();
            $statusCode = '404';
        }

       $response = $this->render('page/error.html.twig', [
            'status_code' => $statusCode,
            'req' => $request->attributes->get('exception'),
            'msg' => $msg,
            'posts' => $this->postRepository->getFeatures()
        ]);

        $response->setSharedMaxAge(3600);
        
        return $response;
    }
}
