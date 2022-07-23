<?php

namespace App\Services\Search\Normalizer;

use App\Entity\Post;
use App\Normalizer\PostPathNormalizer;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class PostNormalizer implements ContextAwareNormalizerInterface
{
    public function __construct(
        private readonly PostPathNormalizer $pathNormalizer,
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly UploaderHelper $helper,
    ) {
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Post && 'search' === $format;
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        if (!$object instanceof Post) {
            throw new \InvalidArgumentException('Unexpected type for normalization, expected Post, got ' . $object::class);
        }
        $url = $this->pathNormalizer->normalize($object);

        return [
            'id'         => (string)$object->getId(),
            'content'    => (string)$object->getContent(),
            'url'        => $this->urlGenerator->generate($url['path'], $url['params']),
            'title'      => $object->getTitle(),
            'category'   => $object->getCategory()->getName(), // @phpstan-ignore-line
            'tags' => array_map(fn($t) =>$t->getName(), (array)  $object->getTags()->toArray()),
            'type'       => 'post',
            'created_at' => $object->getCreatedAt()->getTimestamp(),
            'filename' => $this->helper->asset($object),
        ];
    }
}