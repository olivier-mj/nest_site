<?php

namespace App\Normalizer;

use App\Entity\Tag;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;

class TagNormalizer implements NormalizerInterface, CacheableSupportsMethodInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }


    public function normalize($object, $format = null, array $context = []): mixed
    {
        if ($object instanceof Tag) {
            return [
                'id' => $object->getId(),
                'name' => $object->getName(),
                'slug'=> $object->getSlug(),
                "url" => $this->urlGenerator->generate('categories.show', ['id' =>$object->getId(), 'slug' => $object->getSlug()])
            ];
        }
        throw new \RuntimeException("Can't normalize Tag");

    }

    public function supportsNormalization($data, $fomat = null): bool
    {
        return $data instanceof Tag && 'json' === $fomat;
    }


    public function hasCacheableSupportsMethod(): bool
    {
        return true;
    }
}
