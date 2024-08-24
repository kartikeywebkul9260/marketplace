<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\RefundItem as RefundItemContract;
use Webkul\Sales\Models\RefundItemProxy;

class RefundItem extends Model implements RefundItemContract
{
    public $timestamps = false;

    protected $table = 'marketplace_refund_items';

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
        return $this->belongsTo(RefundItemProxy::modelClass(), 'refund_item_id');
    }
}
