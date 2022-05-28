<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Services\Mailer;
use App\Form\ContactType;
use Psr\Log\LoggerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;


class ContactController extends AbstractController
{

    public function __construct(private Mailer $mailer)
    {
        $this->mailer = $mailer;
    }



    #[Route('/contact', name: 'page.contact', methods: ['GET', 'POST'])]
    public function index(Request $request, MailerInterface $mailer, LoggerInterface $logger): Response
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact, [
            'method' => 'POST',
            'attr' => [
                'id' => 'formContact',
                'class' => 'needs-validation',
                'novalidate' => true
            ]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->mailer->sendContact($contact);

                $this->addFlash(
                    "success",
                    "Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais."
                );
                return $this->redirectToRoute('page.contact');
            } catch (TransportExceptionInterface $error) {

                $this->addFlash(
                    "error",
                    "Votre message n'a malheureusement pas pu être envoyer !!  Veuillez essayer ultérieurement !!"
                );
                $logger->alert($error->getMessage());
            }
        }

        $response = $this->render('page/contact.html.twig', [
            'form' => $form->createView(),
        ]);

        $response->setSharedMaxAge(3600);

        return $response;
    }
}
