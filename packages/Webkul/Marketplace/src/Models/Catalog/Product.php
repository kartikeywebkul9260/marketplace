<?php

namespace Webkul\Marketplace\Models\Catalog;

use Webkul\Product\Models\Product as BaseProduct;

class Product extends BaseProduct
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'attribute_family_id',
        'sku',
        'parent_id',
        'additional',
    ];
}
