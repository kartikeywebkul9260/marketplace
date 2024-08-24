<?php

namespace Webkul\Marketplace;

use Exception;
use Illuminate\Support\Facades\Event;
use Webkul\Checkout\Cart as BaseCart;

class Cart extends BaseCart
{
    /**
     * Get cart item by product.
     *
     * @param  array  $data
     * @param  array|null  $parentData
     * @return \Webkul\Checkout\Contracts\CartItem|void
     */
    public function getItemByProduct($data, $parentData = null)
    {
        $items = $this->getCart()->all_items;

        foreach ($items as $item) {
            if ($item->product->getTypeInstance()->compareOptions($item->additional, $data['additional'])) {
                if (! isset($data['additional']['parent_id'])) {
                    if ($item->additional['product_id'] == $data['additional']['product_id']) {
                        return $item;
                    }
                }

                if ($item->parent->product->getTypeInstance()->compareOptions($item->parent->additional, $parentData ?: request()->all())) {
                    return $item;
                }
            } elseif (
                isset($data['additional']['seller_info'])
                && isset($item->additional['seller_info'])
            ) {
                if (
                    ($data['additional']['seller_info']['product_id'] == $item->additional['seller_info']['product_id'])
                    && ($data['additional']['seller_info']['seller_id'] == $item->additional['seller_info']['seller_id'])
                ) {
                    return $item;
                }
            }
        }
    }

    /**
     * Add items in a cart with some cart and item details.
     *
     * @param  int  $productId
     * @param  array  $data
     * @return \Webkul\Checkout\Contracts\Cart|string|array
     *
     * @throws Exception
     */
    public function addProduct($productId, $data)
    {
        Event::dispatch('checkout.cart.add.before', $productId);

        $cart = $this->getCart();

        if (! $cart) {
            $cart = $this->create($data);
        }

        if (! $cart) {
            return ['warning' => __('shop::app.checkout.cart.item.error-add')];
        }

        if (! empty($data['seller_info'])) {
            $data['seller_info'] = [
                'product_id'    => isset($data['selected_configurable_option']) ? $data['selected_configurable_option'] : $data['seller_info']['product_id'],
                'seller_id'     => $data['seller_info']['seller_id'],
                'is_owner'      => 0,
            ];

            $sellerOwnerProduct = app('Webkul\Marketplace\Repositories\ProductRepository')->findOneWhere([
                'product_id'            => $productId,
                'is_owner'              => 1,
                'marketplace_seller_id' => $data['seller_info']['seller_id'],
            ]);
        } else {
            $sellerOwnerProduct = app('Webkul\Marketplace\Repositories\ProductRepository')->findOneWhere([
                'product_id'    => $productId,
                'is_owner'      => 1,
            ]);
        }

        if (! empty($sellerOwnerProduct)) {
            $data['seller_info'] = [
                'product_id'    => isset($data['selected_configurable_option']) ? $data['selected_configurable_option'] : $productId,
                'seller_id'     => $sellerOwnerProduct->marketplace_seller_id,
                'is_owner'      => 1,
            ];
        }

        $product = $this->productRepository->find($productId);

        if (! $product->status) {
            return ['info' => __('shop::app.checkout.cart.item.inactive-add')];
        }

        $cartProducts = $product->getTypeInstance()->prepareForCart($data);

        if (is_string($cartProducts)) {
            if ($cart->all_items->count() <= 0) {
                $this->removeCart($cart);
            } else {
                $this->collectTotals();
            }

            throw new Exception($cartProducts);
        } else {
            $parentCartItem = null;

            foreach ($cartProducts as $cartProduct) {
                $cartItem = $this->getItemByProduct($cartProduct, $data);

                if (isset($cartProduct['parent_id'])) {
                    $cartProduct['parent_id'] = $parentCartItem->id;
                }

                if (! $cartItem) {
                    $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, ['cart_id' => $cart->id]));
                } else {
                    if (
                        isset($cartProduct['parent_id'])
                        && $cartItem->parent_id !== $parentCartItem->id
                    ) {
                        $cartItem = $this->cartItemRepository->create(array_merge($cartProduct, [
                            'cart_id' => $cart->id,
                        ]));
                    } else {
                        $cartItem = $this->cartItemRepository->update($cartProduct, $cartItem->id);
                    }
                }

                if (! $parentCartItem) {
                    $parentCartItem = $cartItem;
                }
            }
        }

        Event::dispatch('checkout.cart.add.after', $cart);

        $this->collectTotals();

        return $this->getCart();
    }
}
