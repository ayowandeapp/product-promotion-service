<?php

namespace App\DTO;

use App\Entity\Product;
use JsonSerializable;

interface PromotionEnquiryInterface
{
    public function getProduct(): ?Product;
    public function setPromotionId(int $promotionId);

    public function setPromotionName(string $promotionName);
}
