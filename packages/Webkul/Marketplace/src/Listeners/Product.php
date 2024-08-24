<?php

namespace Webkul\Marketplace\Listeners;

use Webkul\Marketplace\Repositories\ProductRepository;

class Product
{
    /**
     * Create a new listener instance.
     *
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository)
    {
    }

    /**
     * Update product for seller if Seller is owner
     */
    public function afterUpdate($product)
    {
        if (
            request()->get('value') == 1
            && request()->route()->getName() == 'admin.catalog.products.mass_update'
            || (
                request()->get('status') == 1
                && request()->route()->getName() == 'admin.catalog.products.update'
            )
        ) {
            $sellerProduct = $this->productRepository->findOneWhere([
                'product_id' => $product->id,
                'is_owner'   => 1,
            ]);

            if ($sellerProduct) {
                $this->productRepository->where('product_id', $product->id)
                    ->update(['is_approved' => 1]);
            }
        }
    }
}
