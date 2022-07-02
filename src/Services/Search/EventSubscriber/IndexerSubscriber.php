<?php

namespace App\Services\Search\EventSubscriber;

use App\EventSubscriber\PostCreatedEvent;
use App\EventSubscriber\PostDeletedEvent;
use App\EventSubscriber\PostUpdatedEvent;
use App\Services\Search\IndexerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexerSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly IndexerInterface $indexer,
        private readonly NormalizerInterface $normalizer
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            PostUpdatedEvent::class => 'updatePost',
            PostCreatedEvent::class => 'indexPost',
            PostDeletedEvent::class => 'removePost',
        ];
    }

    public function indexPost(PostCreatedEvent $event): void
    {
        /** @var array{id: string, title: string, Post: string, created_at: int, category: string[]} $post */
        $post = $this->normalizer->normalize($event->getContent(), 'search');
        if ($event->getContent()->getOnline()) {
            $this->indexer->index($post);
        }
    }

    public function removePost(PostDeletedEvent $event): void
    {
        $this->indexer->remove((string)$event->getContent()->getId());
    }

    public function updatePost(PostUpdatedEvent $event): void
    {
        $previous = $event->getPrevious();
        $current = $event->getContent();
        /** @var array{id: string, title: string, Post: string, created_at: int, category: string[]} $previousData */
        $previousData = $this->normalizer->normalize($previous, 'search');
        /** @var array{id: string, title: string, Post: string, created_at: int, category: string[]} $data */
        $data = $this->normalizer->normalize($current, 'search');
        if ($current->getOnline() && ($previousData !== $data || false === $previous->getOnline())) {
            $this->indexer->index($data);
        } elseif (true === $previous->getOnline() && false === $current->getOnline()) {
            $this->indexer->remove((string)$current->getId());
        }
    }
}