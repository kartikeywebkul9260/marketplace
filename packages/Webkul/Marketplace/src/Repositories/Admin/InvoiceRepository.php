<?php

namespace Webkul\Marketplace\Repositories\Admin;

use Webkul\Sales\Repositories\InvoiceRepository as BaseInvoiceRepository;

class InvoiceRepository extends BaseInvoiceRepository
{
    /**
     * @param  \Webkul\Sales\Contracts\Invoice  $invoice
     * @return \Webkul\Sales\Contracts\Invoice
     */
    public function collectTotals($invoice)
    {
        $invoice->sub_total = $invoice->base_sub_total = 0;
        $invoice->tax_amount = $invoice->base_tax_amount = 0;
        $invoice->discount_amount = $invoice->base_discount_amount = 0;

        foreach ($invoice->items as $invoiceItem) {
            $invoice->sub_total += $invoiceItem->total;
            $invoice->base_sub_total += $invoiceItem->base_total;

            $invoice->tax_amount += $invoiceItem->tax_amount;
            $invoice->base_tax_amount += $invoiceItem->base_tax_amount;

            $invoice->discount_amount += $invoiceItem->discount_amount;
            $invoice->base_discount_amount += $invoiceItem->base_discount_amount;
        }

        $invoice->shipping_amount = $invoice->order->shipping_amount;
        $invoice->base_shipping_amount = $invoice->order->base_shipping_amount;

        $mpProduct = app('Webkul\Marketplace\Repositories\ProductRepository')
            ->findOneWhere([
                'product_id' => $invoiceItem->product_id,
                'price'      => $invoiceItem->price,
            ]);

        $seller = null;

        if ($mpProduct) {
            $seller = $mpProduct->seller;
        }

        if ($seller) {
            $shipping = app('Webkul\Marketplace\Repositories\OrderRepository')
                ->where('order_id', $invoice->order->id)
                ->where('marketplace_seller_id', $seller->id)
                ->first();

            if (
                isset($shipping)
                && $shipping->shipping_amount
            ) {
                $invoice->shipping_amount = $shipping->shipping_amount;
                $invoice->base_shipping_amount = $shipping->shipping_amount;
            }
        }

        $invoice->discount_amount += $invoice->order->shipping_discount_amount;
        $invoice->base_discount_amount += $invoice->order->base_shipping_discount_amount;

        if ($invoice->order->shipping_amount) {

            foreach ($invoice->order->invoices as $prevInvoice) {

                if ((float) $prevInvoice->shipping_amount) {

                    if ($seller) {

                        $shipping = app('Webkul\Marketplace\Repositories\OrderRepository')
                            ->where('order_id', $invoice->order->id)
                            ->where('marketplace_seller_id', $seller->id)
                            ->first();

                        if ($shipping->shipping_amount) {

                            $invoice->shipping_amount = $shipping->shipping_amount;
                        }
                    } else {
                        $invoice->shipping_amount = $invoice->base_shipping_amount = 0;
                    }
                }

                if ($prevInvoice->id != $invoice->id) {
                    $invoice->discount_amount -= $invoice->order->shipping_discount_amount;
                    $invoice->base_discount_amount -= $invoice->order->base_shipping_discount_amount;
                }
            }
        }

        $invoice->grand_total = $invoice->sub_total + $invoice->tax_amount + $invoice->shipping_amount - $invoice->discount_amount;
        $invoice->base_grand_total = $invoice->base_sub_total + $invoice->base_tax_amount + $invoice->base_shipping_amount - $invoice->base_discount_amount;

        $invoice->save();

        return $invoice;
    }
}
