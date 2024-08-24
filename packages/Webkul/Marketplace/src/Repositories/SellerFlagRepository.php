<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\SellerFlag;

class SellerFlagRepository extends Repository
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return SellerFlag::class;
    }
}
