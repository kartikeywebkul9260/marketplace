<?php

namespace Webkul\Marketplace\Repositories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Webkul\Marketplace\Enum\Payout;
use Webkul\Core\Eloquent\Repository;
use Illuminate\Support\Facades\Event;
use Webkul\Marketplace\Contracts\Transaction;

class TransactionRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    public function model()
    {
        return Transaction::class;
    }

    /**
     * Pay seller
     *
     * @param  int  $data
     * @return bool
     */
    public function paySeller($data)
    {
        $sellerOrder = app(OrderRepository::class)->findOneWhere([
            'order_id'              => $data['order_id'],
            'marketplace_seller_id' => $data['seller_id'],
        ]);

        if (! $sellerOrder) {
            session()->flash('error', trans('marketplace::app.admin.orders.order-not-exist'));

            return;
        }

        $totalPaid = $this->scopeQuery(function ($query) use ($sellerOrder) {
            return $query->where('marketplace_transactions.marketplace_seller_id', $sellerOrder->marketplace_seller_id)
                ->where('marketplace_transactions.marketplace_order_id', $sellerOrder->id);
        })->sum('base_total');

        $amount = $sellerOrder->base_seller_total_invoiced - $totalPaid;

        if (! $amount) {
            session()->flash('error', trans('marketplace::app.admin.orders.no-amount-to-paid'));

            return;
        }

        Event::dispatch('marketplace.sales.transaction.create.before', $data);

        $transaction = $this->create([
            'type'                  => isset($data['type']) ? $data['type'] : 'Manual',
            'method'                => isset($data['method']) ? $data['method'] : 'Manual',
            'transaction_id'        => $data['order_id'].'-'.Str::random(10),
            'comment'               => $data['comment'],
            'base_total'            => $amount,
            'marketplace_order_id'  => $sellerOrder->id,
            'marketplace_seller_id' => $sellerOrder->marketplace_seller_id,
        ]);

        if (($amount + $totalPaid) == $sellerOrder->base_seller_total) {
            app(OrderRepository::class)->update([
                'seller_payout_status' => Payout::STATUS_PAID->value,
            ], $sellerOrder->id);
        }

        Event::dispatch('marketplace.sales.transaction.create.after', $transaction);

        return $transaction;
    }

    /**
     * Fet Transaction statistics
     *
     * @param  object  $seller
     * @return array
     */
    public function statistics($seller)
    {
        return [
            'total_sale' => app(OrderRepository::class)->scopeQuery(function ($query) use ($seller) {
                return $query->where('marketplace_orders.marketplace_seller_id', $seller->id);
            })->sum(DB::raw('base_sub_total_invoiced - base_sub_total_refunded')),

            'total_payout' => $this->scopeQuery(function ($query) use ($seller) {
                return $query->where('marketplace_transactions.marketplace_seller_id', $seller->id);
            })->sum('base_total'),

            'remaining_payout' => app(OrderRepository::class)->scopeQuery(function ($query) use ($seller) {
                return $query->where('marketplace_orders.marketplace_seller_id', $seller->id)
                    ->where('status', 'completed')
                    ->whereIn('seller_payout_status', ['pending', 'requested']);
            })->sum('base_sub_total_invoiced'),
        ];
    }
}
