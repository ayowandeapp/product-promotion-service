<?php

namespace App\DTO;

interface PriceEnquiryInterface extends PromotionEnquiryInterface
{
    public function setPrice(int $price);
    public function setDiscountedPrice(int $discountPrice);
    public function getQuantity(): ?int;
}
