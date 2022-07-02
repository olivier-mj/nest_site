<?php

namespace App\EventSubscriber;

use App\Entity\Post;

class PostUpdatedEvent
{
    public function __construct(private readonly Post $content, private readonly Post $previous)
    {
    }

    public function getContent(): Post
    {
        return $this->content;
    }

    public function getPrevious(): Post
    {
        return $this->previous;
    }
}