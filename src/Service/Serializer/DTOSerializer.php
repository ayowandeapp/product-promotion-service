<?php

namespace App\Service\Serializer;

use App\Event\AfterDtoCreatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as EventDispatcherEventDispatcherInterface;

class DTOSerializer implements SerializerInterface
{

    private SerializerInterface $serializer;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherEventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->serializer = new Serializer(
            normalizers: [new ObjectNormalizer(
                nameConverter: new CamelCaseToSnakeCaseNameConverter(),
                defaultContext: ['ignored_attributes' => ['product']]
            )],
            encoders: [new JsonEncoder()]
        );
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        $dto = $this->serializer->deserialize($data, $type, $format, $context);

        $event = new AfterDtoCreatedEvent($dto);
        //dispatch event
        $this->eventDispatcher->dispatch($event);

        return $dto;
    }
}
