<?php


namespace App\Tests\Unit;

use App\Entity\Product;
use App\Entity\Promotion;
use App\Tests\ServiceTestCase;
use App\DTO\LowestPriceEnquiry;
use App\Filter\LowestPriceFilter;
use App\Filter\Modifier\FixedPriceVoucher;
use App\Filter\Modifier\DateRangeMultiplier;
use App\Filter\Modifier\EvenItemsMultiplier;

class LowestPriceFilterTest extends ServiceTestCase
{

    public function test_lowest_price_promotions_filtering_is_applied_correctly()
    {
        $product = new Product();
        $product->setPrice(100);


        $enquiry = new LowestPriceEnquiry();
        $enquiry->setProduct($product);
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate('2022-11-27');
        $enquiry->setVoucherCode('OU812');

        $promotions = $this->promotionsDataProvider();

        $lowestPriceFilter = $this->container->get(LowestPriceFilter::class);

        $filtered = $lowestPriceFilter->apply($enquiry, ...$promotions);

        $this->assertSame(100,  $filtered->getPrice());
        $this->assertSame(250,  $filtered->getDiscountedPrice());
        $this->assertSame(strtolower('Black Friday half price sale'),  $filtered->getPromotionName());
    }

    public function promotionsDataProvider(): array
    {
        $promotion1 = new Promotion;
        $promotion1->setName('black friday half price sale');
        $promotion1->setAdjustment(0.5);
        $promotion1->setCriteria(['from' => '2022-11-25', 'to' => '2022-11-28']);
        $promotion1->setType('date_range_multiplier');

        $promotion2 = new Promotion;
        $promotion2->setName('voucher OU812');
        $promotion2->setAdjustment(100);
        $promotion2->setCriteria(['code' => 'OU812']);
        $promotion2->setType('fixed_price_voucher');

        $promotion3 = new Promotion;
        $promotion3->setName('buy one get one free');
        $promotion3->setAdjustment(0.5);
        $promotion3->setCriteria(['minimum_quantity' => 2]);
        $promotion3->setType('even_items_multiplier');

        return [$promotion1, $promotion2, $promotion3];
    }


    public function test_date_range_multiplier_returns_a_correctly_modified_price()
    {
        $promotion1 = new Promotion;
        $promotion1->setName('black friday half price sale');
        $promotion1->setAdjustment(0.5);
        $promotion1->setCriteria(['from' => '2022-11-25', 'to' => '2022-11-28']);
        $promotion1->setType('date_range_multiplier');

        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);
        $enquiry->setRequestDate('2022-11-27');

        $dateRangeMod = new DateRangeMultiplier;

        $modPrice = $dateRangeMod->modify(100, 5, $promotion1, $enquiry);

        $this->assertEquals(250, $modPrice);
    }

    public function test_fixed_price_returns_a_correctly_modified_price()
    {

        $promotion2 = new Promotion;
        $promotion2->setName('voucher OU812');
        $promotion2->setAdjustment(100);
        $promotion2->setCriteria(['code' => 'OU812']);
        $promotion2->setType('fixed_price_voucher');

        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);
        $enquiry->setVoucherCode('OU812');

        $fixedPriceVoucher = new FixedPriceVoucher;
        $modPrice = $fixedPriceVoucher->modify(150, 5, $promotion2, $enquiry);

        $this->assertEquals(500, $modPrice);
    }


    public function test_even_item_multiplier_returns_a_correctly_modified_price()
    {
        $promotion3 = new Promotion;
        $promotion3->setName('buy one get one free');
        $promotion3->setAdjustment(0.5);
        $promotion3->setCriteria(['minimum_quantity' => 2]);
        $promotion3->setType('even_items_multiplier');

        $enquiry = new LowestPriceEnquiry();
        $enquiry->setQuantity(5);

        $evenItemMod = new EvenItemsMultiplier;

        $modPrice = $evenItemMod->modify(100, 5, $promotion3, $enquiry);

        $this->assertEquals(300, $modPrice);
    }
}
