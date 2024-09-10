<?php

namespace App\Filter\Modifier;

class EvenItemsMultiplier implements PriceModifierInterface
{
    public function modify(int $price, int $quantity, \App\Entity\Promotion $promotion, \App\DTO\PromotionEnquiryInterface $enquiry): int
    {
        $total = $price * $quantity;

        $odd = $quantity % 2; //0 or 1
        $evenCount = $quantity - $odd;

        if ($quantity >= $promotion->getCriteria()['minimum_quantity']) {

            return ($evenCount * $price * $promotion->getAdjustment()) + ($odd * $price);
        }
        return $total;
    }
}
