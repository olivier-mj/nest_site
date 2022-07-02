<?php

namespace App\EventSubscriber;

use App\Entity\Post;

class PostDeletedEvent
{
    public function __construct(private readonly Post $content)
    {
    }

    public function getContent(): Post
    {
        return $this->content;
    }
}
