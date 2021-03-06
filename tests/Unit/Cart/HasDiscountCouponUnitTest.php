<?php

namespace Happypixels\Shopr\Tests\Unit\Cart;

use Happypixels\Shopr\Cart\Cart;
use Happypixels\Shopr\Tests\TestCase;
use Happypixels\Shopr\Models\DiscountCoupon;
use Happypixels\Shopr\Tests\Support\Models\TestShoppable;

class HasDiscountCouponUnitTest extends TestCase
{
    /** @test */
    public function it_returns_false_if_discount_is_not_applied()
    {
        $cart = $this->addCartItem();

        $this->assertFalse($cart->hasDiscount('CODE'));
    }

    /** @test */
    public function it_returns_true_if_match_is_found()
    {
        $discount = factory(DiscountCoupon::class)->create(['code' => 'Code']);
        $cart = $this->addCartItem();
        $cart->addDiscount($discount);

        $this->assertTrue($cart->hasDiscount('Code'));
    }

    /** @test */
    public function it_is_case_sensitive()
    {
        $discount = factory(DiscountCoupon::class)->create(['code' => 'Code']);
        $cart = $this->addCartItem();
        $cart->addDiscount($discount);

        $this->assertFalse($cart->hasDiscount('CODE'));
        $this->assertFalse($cart->hasDiscount('CodE'));
        $this->assertFalse($cart->hasDiscount('CoDe'));
        $this->assertTrue($cart->hasDiscount('Code'));
    }

    /** @test */
    public function it_looks_for_any_discount_if_code_is_empty()
    {
        $discount = factory(DiscountCoupon::class)->create(['code' => 'Code']);
        $cart = $this->addCartItem();

        $this->assertFalse($cart->hasDiscount());

        $cart->addDiscount($discount);

        $this->assertTrue($cart->hasDiscount());
    }

    public function addCartItem()
    {
        $cart = app(Cart::class);
        $model = factory(TestShoppable::class)->create(['price' => 500]);
        $cart->addItem(get_class($model), $model->id, 3);

        return $cart;
    }
}
