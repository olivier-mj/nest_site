<?php

namespace App\Normalizer;

use App\Entity\Attachment;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\Asset\PackageInterface;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FileNormalizer implements NormalizerInterface
{


    public function __construct(
        private UploaderHelper $uploaderHelper,
        private CacheManager $imagineCacheManager,
        private PackageInterface $package
    ) {
        $this->uploaderHelper = $uploaderHelper;
        $this->imagineCacheManager = $imagineCacheManager;
        $this->package = $package;
    }

    public function normalize($object, $format = null, array $context = []): mixed
    {

        if ($object instanceof Attachment) {
            return [
                'src' =>  $this->package->getUrl((string)$this->uploaderHelper->asset($object)),
                'thumbnail' =>  $this->imagineCacheManager->getBrowserPath((string)$this->uploaderHelper->asset($object), "filebrowser", [], null),
                'name' => $object->getFileName(),
                'alt' => $object->getFileName(),
            ];
        }
        throw new \RuntimeException("Can't normalize Attachment");
    }

    public function supportsNormalization($data, $fomat = null): bool
    {
        return $data instanceof Attachment && 'json' === $fomat;
    }
}
