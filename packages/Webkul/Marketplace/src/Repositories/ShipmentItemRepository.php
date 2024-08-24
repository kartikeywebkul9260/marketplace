<?php

namespace Webkul\Marketplace\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Marketplace\Contracts\ShipmentItem;

class ShipmentItemRepository extends Repository
{
    protected $guarded = [
        'id',
        'child',
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
        return ShipmentItem::class;
    }
}
