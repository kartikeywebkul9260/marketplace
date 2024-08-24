<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account\Orders;

use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Enum\Payout;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Mail\PaymentRequestNotification;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\TransactionRepository;
use Webkul\User\Repositories\AdminRepository;

class PaymentRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected TransactionRepository $transactionRepository,
        protected AdminRepository $adminRepository
    ) {
    }

    /**
     * Update the order for payment and sends mails to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function requestPayment($id)
    {
        $order = $this->orderRepository->find($id);

        if (
            $order
            && ! in_array($order->seller_payout_status, [
                Payout::STATUS_PAID->value,
                Payout::STATUS_REFUNDED->value,
                Payout::STATUS_REQUESTED->value,
            ])
        ) {
            $order->update(['seller_payout_status' => Payout::STATUS_REQUESTED->value]);

            try {
                Mail::to(core()->getAdminEmailDetails()['email'])
                    ->send(new PaymentRequestNotification($order));
            } catch (\Exception $e) {
                report($e);
            }

            session()->flash('success', trans('marketplace::app.shop.sellers.account.orders.index.payment-req-success'));
        }

        return Back();
    }
}
