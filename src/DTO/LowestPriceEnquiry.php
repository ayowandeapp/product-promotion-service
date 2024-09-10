<?php

namespace App\DTO;

use App\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;


class LowestPriceEnquiry implements PriceEnquiryInterface
{
    private ?Product $product;

    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $quantity;

    private ?string $requestLocation;

    private ?string $voucherCode;

    #[Assert\NotBlank]
    private ?string $requestDate;

    #[Assert\Positive]
    private ?int $price;

    private ?int $discountedPrice;

    private ?int $promotionId;

    private ?string $promotionName;


    public function getProduct(): ?Product
    {
        return $this->product;
    }
    public function setProduct(?Product $value): void
    {
        $this->product = $value;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }
    public function setQuantity(?int $value): void
    {
        $this->quantity = $value;
    }

    public function getRequestLocation(): ?string
    {
        return $this->requestLocation;
    }
    public function setRequestLocation(?string $value): void
    {
        $this->requestLocation = $value;
    }

    public function getVoucherCode(): ?string
    {
        return $this->voucherCode;
    }
    public function setVoucherCode(?string $value): void
    {
        $this->voucherCode = $value;
    }

    public function getRequestDate(): ?string
    {
        return $this->requestDate;
    }
    public function setRequestDate(?string $value): void
    {
        $this->requestDate = $value;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }
    public function setPrice(?int $value): void
    {
        $this->price = $value;
    }

    public function getDiscountedPrice(): ?int
    {
        return $this->discountedPrice;
    }
    public function setDiscountedPrice(?int $value): void
    {
        $this->discountedPrice = $value;
    }

    public function getPromotionId(): ?int
    {
        return $this->promotionId;
    }
    public function setPromotionId(?int $value): void
    {
        $this->promotionId = $value;
    }

    public function getPromotionName(): ?string
    {
        return $this->promotionName;
    }
    public function setPromotionName(?string $value): void
    {
        $this->promotionName = $value;
    }

    // public function jsonSerialize(): mixed
    // {
    //     return get_object_vars($this);
    // }
}
