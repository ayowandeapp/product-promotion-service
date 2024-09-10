<?php


namespace App\Filter;

use App\Entity\Promotion;
use App\DTO\PriceEnquiryInterface;
use App\Filter\Modifier\Factory\PriceModifierFactoryInterface;

class LowestPriceFilter implements PriceFilterInterface
{
    public function __construct(private PriceModifierFactoryInterface $priceModifierFactory) {}
    public function apply(PriceEnquiryInterface $enquiry, Promotion ...$promotions): PriceEnquiryInterface
    {
        $price = $enquiry->getProduct()->getPrice();
        $enquiry->setPrice($price);
        $quantity = $enquiry->getQuantity();
        $lowestPrice = $price * $quantity;
        // dd($promotions);

        foreach ($promotions as $promotion) {
            $priceMod = $this->priceModifierFactory->create($promotion->getType());

            $modPrice = $priceMod->modify($price, $quantity, $promotion, $enquiry);
            //check if modprice is lower than the lowestprice
            if ($modPrice < $lowestPrice) {

                $enquiry->setDiscountedPrice($modPrice);
                $enquiry->setPromotionId($promotion->getId());
                $enquiry->setPromotionName($promotion->getName());
                $lowestPrice = $modPrice;
            }
        }

        return $enquiry;
    }
}
