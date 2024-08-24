<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Marketplace\Contracts\ProductImage;

class ProductImageRepository extends ProductMediaRepository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return ProductImage::class;
    }

    /**
     * Upload images.
     *
     * @param  array  $data
     * @param  object  $product
     */
    public function uploadImages($data, $product): void
    {
        $this->upload($data, $product, 'images');
    }

    /**
     * Upload variant images.
     *
     * @param  array  $variants
     * @param  object  $product
     */
    public function uploadVariantImages($variants, $product): void
    {
        $this->upload($variants, $product, 'images');
    }
}
