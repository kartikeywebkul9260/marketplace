<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\Order as OrderContract;
use Webkul\Sales\Models\OrderProxy;

class Order extends Model implements OrderContract
{
    protected $table = 'marketplace_orders';

    protected $guarded = ['_token'];

    protected $statusLabel = [
        'pending'         => 'Pending',
        'pending_payment' => 'Pending Payment',
        'payment_request' => 'Payment Requested',
        'processing'      => 'Processing',
        'completed'       => 'Completed',
        'canceled'        => 'Canceled',
        'closed'          => 'Closed',
        'fraud'           => 'Fraud',
    ];

    /**
     * Returns the status label from status code
     */
    public function getStatusLabelAttribute()
    {
        return $this->statusLabel[$this->status];
    }

    /**
     * Return base total due amount
     */
    public function getBaseTotalDueAttribute()
    {
        return $this->base_grand_total - $this->base_grand_total_invoiced;
    }

    /**
     * Return total due amount
     */
    public function getTotalDueAttribute()
    {
        return $this->grand_total - $this->grand_total_invoiced;
    }

    /**
     * Get the seller that belongs to the order.
     */
    public function seller()
    {
        return $this->belongsTo(SellerProxy::modelClass(), 'marketplace_seller_id');
    }

    /**
     * Get the order that belongs to the order.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the order items record associated with the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItemProxy::modelClass(), 'marketplace_order_id')->whereNull('parent_id');
    }

    /**
     * Get the order shipments record associated with the order.
     */
    public function shipments()
    {
        return $this->hasMany(ShipmentProxy::modelClass(), 'marketplace_order_id');
    }

    /**
     * Get the order invoices record associated with the order.
     */
    public function invoices()
    {
        return $this->hasMany(InvoiceProxy::modelClass(), 'marketplace_order_id');
    }

    /**
     * Get the order Refunds record associated with the order.
     */
    public function refunds()
    {
        return $this->hasMany(RefundProxy::modelClass(), 'marketplace_order_id');
    }

    /**
     * Get the transactions items record associated with the order.
     */
    public function transactions()
    {
        return $this->hasMany(TransactionProxy::modelClass(), 'marketplace_order_id');
    }

    /**
     * Checks if new shipment is allow or not
     */
    public function canShip()
    {
        if ($this->status == 'fraud') {
            return false;
        }

        foreach ($this->items as $sellerOrderItem) {
            if ($sellerOrderItem->item->qty_to_ship > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if new invoice is allow or not
     */
    public function canInvoice()
    {
        if ($this->status == 'fraud') {
            return false;
        }

        foreach ($this->items as $sellerOrderItem) {
            if ($sellerOrderItem->item->qty_to_invoice > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if order could can canceled on not
     */
    public function canCancel()
    {
        if ($this->status == 'fraud') {
            return false;
        }

        foreach ($this->items as $sellerOrderItem) {
            if ($sellerOrderItem->item->qty_to_cancel > 0) {
                return true;
            }
        }

        return false;
    }
}
