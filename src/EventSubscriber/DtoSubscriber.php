<?php

namespace App\EventSubscriber;

use App\Event\AfterDtoCreatedEvent;
use App\Service\ServiceException;
use App\Service\ServiceExceptionData;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DtoSubscriber implements EventSubscriberInterface
{

    public function __construct(private ValidatorInterface $validator) {}

    public static function getSubscribedEvents(): array
    {
        return [
            AfterDtoCreatedEvent::class => [
                "validateDto"
            ],
        ];
    }

    public function validateDto(AfterDtoCreatedEvent $event)
    {
        $dto = $event->getDto();
        $errors = $this->validator->validate($dto);

        if (count($errors) > 0) {
            $validationException = new ServiceExceptionData(422, 'ConstraintViolationList', $errors);
            throw new ServiceException($validationException);
        }
    }
}
