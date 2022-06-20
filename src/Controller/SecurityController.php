<?php

namespace App\Controller;

use App\Services\Securizer;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'security.login')]
    public function login(AuthenticationUtils $authenticationUtils, Securizer $securizer): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('page.home');
        // }

        // if($securizer->isGranted($this->getUserOrThrow(), 'ROLE_ADMIN')){
        //     return $this->redirectToRoute('page.home');
        // }
        if($this->isGranted('ROLE_ADMIN')){
            return $this->redirectToRoute('admin.dashboard');
        }

        if($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('page.home');
        }
        
        // if($securizer->isGranted($this->getUserOrThrow(), 'ROLE_USER')){
        //     return $this->redirectToRoute('page.home');
        // }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'security.logout')]
    public function logout(): void
    {
        return;
    }
}
