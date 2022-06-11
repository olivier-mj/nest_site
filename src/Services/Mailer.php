<?php

namespace App\Services;

use App\Entity\Contact;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;


class Mailer
{
    public function __construct(private MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

  
    public function sendContact(Contact $contact): TemplatedEmail
    {
        if (empty($contact->getSubject())) {
            $subject = 'Message de ' . $contact->getNickname();
        } else {
            $subject = $contact->getSubject();
        }

        $email = (new TemplatedEmail())
            ->subject($subject)
            ->from(new Address('formulaire@nest-gaming.fr', 'Formulaire de contact'))
            ->to(new Address('contact@nest-gaming.fr', 'Contact'))
            ->replyTo(new Address($contact->getEmail(), $contact->getNickname()))
            ->htmlTemplate('emails/contact/contactForm.html.twig')
            ->context([
                "contact" => $contact,
                "nickname" => $contact->getNickname(),
                "subject" => $subject,
                "replay" => $contact->getEmail(),
                "content" => $contact->getContent()
            ]);



            $this->mailer->send($email);
            return $email;
        
    }


}
