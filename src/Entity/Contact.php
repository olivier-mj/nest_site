<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
class Contact
{
    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=2, max=100)
     */
    private string $nickname;


    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private string $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=5)
     */
    private string $subject;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=10)
     */
    private string $content;

    /**
     * @return string
     */
    public function getNickname(): string
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     * @return Contact
     */
    public function setNickname(string $nickname): Contact
    {
        $this->nickname = $nickname;
        return $this;
    }


    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return Contact
     */
    public function setEmail(string $email): Contact
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     * @return Contact
     */
    public function setSubject(string $subject): Contact
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     * @return Contact
     */
    public function setContent(string $content): Contact
    {
        $this->content = $content;
        return $this;
    }


}