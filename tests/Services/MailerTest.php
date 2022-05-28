<?php

namespace App\Services\Tests;

use App\Entity\Contact;
use App\Services\Mailer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;

class MailerTest extends TestCase
{
    public function testSendEmailContactForm(): void
    {
        $symfonyMailer = $this->createMock(MailerInterface::class);
        $symfonyMailer->expects($this->once())->method('send');  // @phpstan-ignore-line

        // dump($symfonyMailer);
        $contact = new Contact();
        $contact->setNickname('John Doe');
        $contact->setEmail('john@doe.fr');
        $contact->setSubject('test message send with symfony');
        $contact->setContent('test message send with symfony');

        // dump($contact) ;
        $mailer = new Mailer($symfonyMailer);

        $email = $mailer->sendContact($contact);


    
        $this->assertSame('test message send with symfony', $email->getSubject());
        $this->assertCount(1, $email->getReplyTo());

        /** @var Address[] $namedAddresses */
        $namedAddresses = $email->getReplyTo();

        $this->assertCount(1, $namedAddresses);

        $this->assertInstanceOf(Address::class, $namedAddresses[0]);
        $this->assertSame('John Doe', $namedAddresses[0]->getName());
        $this->assertSame('john@doe.fr', $namedAddresses[0]->getAddress());
    }


}
