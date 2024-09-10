<?php

namespace App\Filter\Modifier;

use App\Entity\Promotion;
use App\DTO\PromotionEnquiryInterface;

class FixedPriceVoucher implements PriceModifierInterface
{
    public function modify(int $price, int $quantity, Promotion $promotion, PromotionEnquiryInterface $enquiry): int
    {
        if ($promotion->getCriteria()['code'] !== $enquiry->getVoucherCode()) {
            return $quantity * $price;
        }

        return $promotion->getAdjustment() * $quantity;
    }
}
