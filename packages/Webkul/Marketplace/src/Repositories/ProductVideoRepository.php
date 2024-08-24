<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Marketplace\Contracts\ProductVideo;

class ProductVideoRepository extends ProductMediaRepository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return ProductVideo::class;
    }

    /**
     * Upload videos.
     *
     * @param  array  $data
     * @param  object  $product
     * @return void
     */
    public function uploadVideos($data, $product)
    {
        $this->upload($data, $product, 'videos');
    }

    /**
     * Upload variant videos.
     *
     * @param  array  $variants
     * @param  object  $product
     */
    public function uploadVariantVideos($variants, $product): void
    {
        $this->upload($variants, $product, 'videos');
    }
}
