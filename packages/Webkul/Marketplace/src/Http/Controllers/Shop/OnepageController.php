<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Facades\Cart;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductRepository as BaseProductRepository;

class OnepageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected ProductRepository $productRepository,
        protected BaseProductRepository $baseProductRepository
    ) {
    }

    public function index()
    {
        Event::dispatch('checkout.load.index');

        if (
            core()->getConfigData('marketplace.settings.general.status')
            && core()->getConfigData('marketplace.settings.general.enable_minimum_order_amount')
        ) {
            if (! $this->checkCartTotal()) {
                return Back();
            }
        }

        /**
         * If guest checkout is not allowed then redirect back to the cart page
         */
        if (
            ! auth()->guard('customer')->check()
            && ! core()->getConfigData('catalog.products.guest_checkout.allow_guest_checkout')
        ) {
            return redirect()->route('shop.customer.session.index');
        }

        /**
         * If user is suspended then redirect back to the cart page
         */
        if (auth()->guard('customer')->user()?->is_suspended) {
            session()->flash('warning', trans('shop::app.checkout.cart.suspended-account-message'));

            return redirect()->route('shop.checkout.cart.index');
        }

        /**
         * If cart has errors then redirect back to the cart page
         */
        if (Cart::hasError()) {
            return redirect()->route('shop.checkout.cart.index');
        }

        $cart = Cart::getCart();

        /**
         * If cart is has downloadable items and customer is not logged in
         * then redirect back to the cart page
         */
        if (
            ! auth()->guard('customer')->check()
            && (
                $cart->hasDownloadableItems()
                || ! $cart->hasGuestCheckoutItems()
            )
        ) {
            return redirect()->route('shop.customer.session.index');
        }

        /**
         * If cart minimum order amount is not satisfied then redirect back to the cart page
         */
        $minimumOrderAmount = (float) core()->getConfigData('sales.order_settings.minimum_order.minimum_order_amount') ?: 0;

        if (! $cart->checkMinimumOrder()) {
            session()->flash('warning', trans('shop::app.checkout.cart.minimum-order-message', [
                'amount' => core()->currency($minimumOrderAmount),
            ]));

            return Back();
        }

        return view('shop::checkout.onepage.index');
    }

    /**
     * Product added to the cart
     *
     * @param  mixed  $cartItem
     */
    public function checkCartTotal()
    {
        $cart = Cart::getCart();

        if ($cart) {
            $productsAmount = [];

            foreach ($cart->items as $item) {
                if (
                    ! empty($item->additional['seller_info'])
                    && ! empty($item->additional['seller_info']['seller_id'])
                ) {
                    if (array_key_exists($item->additional['seller_info']['seller_id'], $productsAmount)) {
                        $productsAmount[$item->additional['seller_info']['seller_id']] += $item->total;
                    } else {
                        $productsAmount[$item->additional['seller_info']['seller_id']] = $item->total;
                    }
                }
            }

            $sellers = $this->sellerRepository->findWhereIn('id', array_keys($productsAmount));

            foreach ($sellers as $seller) {
                if (
                    $seller->min_order_amount
                    && $productsAmount[$seller->id] < $seller->min_order_amount
                ) {
                    session()->flash('warning', trans('marketplace::app.shop.checkout.cart.minimum-order-message', [
                        'shop_title' => $seller->shop_title,
                        'amount'     => core()->currency($seller->min_order_amount),
                    ]));

                    return false;
                }
            }

            return true;
        }
    }
}
