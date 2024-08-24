<?php

namespace Webkul\Marketplace\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Marketplace\Contracts\Invoice as InvoiceContract;
use Webkul\Sales\Models\InvoiceProxy;

class Invoice extends Model implements InvoiceContract
{
    protected $table = 'marketplace_invoices';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the Invoice that belongs to the Invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(InvoiceProxy::modelClass(), 'invoice_id');
    }

    /**
     * Get the Invoice items record associated with the Invoice.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItemProxy::modelClass(), 'marketplace_invoice_id');
    }

    /**
     * Get the order that belongs to the invocie.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass(), 'marketplace_order_id');
    }
}
