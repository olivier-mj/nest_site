<?php

namespace App\EventSubscriber;

use App\Entity\Post;

class PostCreatedEvent
{
    public function __construct(private readonly Post $content)
    {
    }

    public function getContent(): Post
    {
        return $this->content;
    }
}
