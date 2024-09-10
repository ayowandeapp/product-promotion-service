<?php

namespace App\Tests\unit;

use App\DTO\LowestPriceEnquiry;
use App\Event\AfterDtoCreatedEvent;
use App\EventSubscriber\DtoSubscriber;
use App\Service\ServiceException;
use App\Tests\ServiceTestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class DtoSubscriberTest extends ServiceTestCase
{
    public function test_event_is_subscribed_to()
    {
        // dd(DtoSubscriber::getSubscribedEvents());
        $this->assertArrayHasKey(AfterDtoCreatedEvent::class, DtoSubscriber::getSubscribedEvents());
    }
    public function test_dto_validated_after_it_been_created()
    {
        $dto = new LowestPriceEnquiry();
        $dto->setQuantity(-5);
        // dd($dto);

        $event = new AfterDtoCreatedEvent($dto);
        /**
         * @var  EventDispatcher $eventDispatcher 
         */
        $eventDispatcher = $this->container->get("debug.event_dispatcher");

        //what should happen once the event is broadcasted
        $this->expectException(ServiceException::class);
        // $this->expectExceptionMessage("validation failed");

        $eventDispatcher->dispatch($event);
    }
}
