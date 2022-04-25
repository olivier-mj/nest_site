<?php

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Symfony\Component\Form\DataTransformerInterface;
use function Symfony\Component\String\u;

class TagArrayToStringTransformer implements DataTransformerInterface
{
    private TagRepository $tags;

    public function __construct(TagRepository $tags)
    {
        $this->tags = $tags;
    }
    /**
     * @inheritDoc
     */
    public function transform($tags): string
    {
        return  implode(',', $tags);
    }

    /**
     * @inheritDoc
     */
    public function reverseTransform($string): array
    {
        if (null === $string || u($string)->isEmpty()) {
            return  [];
        }

        $names =  array_filter(array_unique(array_map('trim', u($string)->split((',')))));/* @phpstan-ignore-line */
        $tags = $this->tags->findBy([
            'name' => $names,
        ]);

        $newNames = array_diff($names, $tags);
        foreach ($newNames as $name) {
            $tag = new Tag();
            $tag->setName($name);
            $tags[] = $tag;
        }
        return  $tags;
    }
}
