<?php

namespace App\Serializer;

use App\Entity\Media;
use ArrayObject;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class MediaNormalizer implements NormalizerInterface
{
    function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,

        private UrlGeneratorInterface $router,
    ) {}

    function normalize(mixed $object, ?string $format = null, array $context = []): array|string|int|float|bool|ArrayObject|null
    {
        $data = $this->normalizer->normalize($object, $format, $context);
        $data['href'] = $this->router->generate('app_api_get_media', [
            'id' => $data['id']
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Media;
    }

    function getSupportedTypes(?string $format): array
    {
        return [
            Media::class => true
        ];
    }
}
