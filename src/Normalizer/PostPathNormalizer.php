<?php

namespace App\Normalizer;

use App\Entity\Post;
use App\Services\PathEncoder;
use App\Normalizer\Normalizer;

class PostPathNormalizer extends Normalizer
{
    public function normalize($object, string $format = null, array $context = []): array
    {
        if ($object instanceof Post) {
            return [
                'path' => 'blog.show',
                'params' => ['slug' => $object->getSlug(), 'id' => $object->getId()],
            ];
        }
        throw new \RuntimeException("Can't normalize path");
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return ($data instanceof Post)
            && PathEncoder::FORMAT === $format;
    }
}
