<?php

namespace Webkul\Marketplace\Listeners;

use Webkul\Checkout\Facades\Cart as CartFacade;
use Webkul\Checkout\Repositories\CartItemRepository;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Product\Repositories\ProductRepository as CoreProductRepository;

class Cart
{
    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository  $sellerRepository
     * @param  Webkul\Marketplace\Repositories\ProductRepository  $productRepository
     * @param  Webkul\Product\Repositories\ProductRepository  $coreProductRepository
     * @param  Webkul\Checkout\Repositories\CartItemRepository  $cartItemRepository
     * @return void
     */
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected ProductRepository $productRepository,
        protected CoreProductRepository $coreProductRepository,
        protected CartItemRepository $cartItemRepository
    ) {
    }

    /**
     * Product added to the cart
     *
     * @param  int  $productId
     * @return void
     */
    public function cartItemAddBefore($productId)
    {
        $data = request()->all();

        if (
            isset($data['seller_info'])
            && ! $data['seller_info']['is_owner']
        ) {
            $sellerProduct = $this->productRepository->findOneWhere([
                'id'                    => $data['seller_info']['product_id'],
                'marketplace_seller_id' => $data['seller_info']['seller_id'],
            ]);
        } else {
            if (isset($data['selected_configurable_option'])) {
                $sellerProduct = $this->productRepository->findOneWhere([
                    'product_id' => $data['selected_configurable_option'],
                    'is_owner'   => 1,
                ]);
            } else {
                $sellerProduct = $this->productRepository->findOneWhere([
                    'product_id' => $productId,
                    'is_owner'   => 1,
                ]);
            }
        }

        if (! $sellerProduct) {
            return;
        }

        if (! isset($data['quantity'])) {
            $data['quantity'] = 1;
        }

        $product = $this->coreProductRepository->findOneByField('id', $productId);

        if ($cart = CartFacade::getCart()) {
            $cartItem = $cart->items()->where('product_id', $sellerProduct->product_id)->first();

            if ($cartItem) {
                if (! $sellerProduct->haveSufficientQuantity($data['quantity'])
                && $product->haveSufficientQuantity($data['quantity'])) {
                    return;
                } elseif (! $sellerProduct->haveSufficientQuantity($data['quantity'])) {

                    throw new \Exception('Requested quantity not available.');
                }

                $quantity = $cartItem->quantity + $data['quantity'];
            } else {
                $quantity = $data['quantity'];
            }
        } else {
            $quantity = $data['quantity'];
        }

        if (
            ! $sellerProduct->haveSufficientQuantity((int) $quantity)
            && $product->haveSufficientQuantity((int) $quantity)
        ) {
            return;
        } elseif (! $sellerProduct->haveSufficientQuantity($quantity)) {
            throw new \Exception('Requested quantity not available.');
        }
    }

    /**
     * Product added to the cart
     */
    public function cartItemAddAfter()
    {
        $cartItems = $this->cartItemRepository->get();

        foreach ($cartItems as $items) {
            if (
                isset($items->additional['seller_info'])
                && ! $items->additional['seller_info']['is_owner']
            ) {
                $product = $this->productRepository->findOneWhere([
                    'marketplace_seller_id' => $items->additional['seller_info']['seller_id'],
                    'id'                    => $items->additional['product_id'],
                ]);

                if ($product) {
                    $items->price = core()->convertPrice($product->price);
                    $items->base_price = $product->price;
                    $items->custom_price = $product->price;
                    $items->total = core()->convertPrice($product->price * $items->quantity);
                    $items->base_total = $product->price * $items->quantity;

                    if ($items->product->type == 'downloadable') {

                        $data = request()->all();

                        foreach ($product->downloadable_links as $link) {
                            if (! in_array($link->id, $data['links'])) {
                                continue;
                            }

                            $items->price += core()->convertPrice($link->price);
                            $items->base_price += $link->price;
                            $items->custom_price += $link->price;
                            $items->total += (core()->convertPrice($link->price) * $items->quantity);
                            $items->base_total += ($link->price * $items->quantity);
                        }
                    }

                    $items->save();
                }

                $items->save();
            } else {
                $items->save();
            }
        }
    }
}
