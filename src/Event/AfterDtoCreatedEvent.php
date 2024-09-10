<?php

namespace App\Event;

use App\DTO\PromotionEnquiryInterface;
use Symfony\Contracts\EventDispatcher\Event;

class AfterDtoCreatedEvent extends Event
{
    public const NAME = "validateDto";

    public function __construct(protected PromotionEnquiryInterface $dto) {}

    public function getDto(): PromotionEnquiryInterface
    {
        return $this->dto;
    }
}
