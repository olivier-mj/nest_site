<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorController extends AbstractController
{

    #[Route('/author/{username}', name: 'author')]
    public function profilUser(string $username, UserRepository $user): Response
    {
        $author = $user->findOneBy(['username' => $username]);
        
        if ( $author === null ){
            throw new NotFoundHttpException(
                'Le profil demander n\'exite pas', null, 404);
        }


        return $this->render('author/index.html.twig', [
            'author' => $author,
            // 'posts'   => $pagination,
            'posts'   => $author->getPosts()

        ]);
    }
}
