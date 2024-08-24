<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\ShipmentItem as ShipmentItemContract;
use Webkul\Sales\Models\ShipmentItemProxy;

class ShipmentItem extends Model implements ShipmentItemContract
{
    public $timestamps = false;

    protected $table = 'marketplace_shipment_items';

    protected $guarded = [
        'id',
        'child',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the item that belongs to the item.
     */
    public function item()
    {
        return $this->belongsTo(ShipmentItemProxy::modelClass(), 'shipment_item_id');
    }
}
