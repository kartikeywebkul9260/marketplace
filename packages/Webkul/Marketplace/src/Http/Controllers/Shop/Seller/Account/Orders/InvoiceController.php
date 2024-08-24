<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account\Orders;

use Barryvdh\DomPDF\Facade\Pdf;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\InvoiceRepository;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Sales\Repositories\InvoiceRepository as BaseInvoiceRepository;

class InvoiceController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        protected OrderRepository $orderRepository,
        protected InvoiceRepository $invoiceRepository,
        protected BaseInvoiceRepository $baseInvoiceRepository
    ) {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int  $orderId
     * @return \Illuminate\Http\Response
     */
    public function store($orderId)
    {
        $sellerOrder = $this->orderRepository->findOneWhere([
            'order_id'              => $orderId,
            'marketplace_seller_id' => auth()->guard('seller')->user()->id,
        ]);

        if (! $sellerOrder->canInvoice()) {
            session()->flash('error', trans('marketplace::app.shop.sellers.account.orders.invoices.permission-error'));

            return Back();
        }

        $this->validate(request(), [
            'invoice.items.*' => 'required|numeric|min:0',
        ]);

        $data = request()->input();

        $haveProductToInvoice = false;

        foreach ($data['invoice']['items'] as $qty) {
            if ($qty) {
                $haveProductToInvoice = true;
                break;
            }
        }

        if (! $haveProductToInvoice) {
            session()->flash('error', trans('marketplace::app.shop.sellers.account.orders.invoices.qty-error'));

            return Back();
        }

        $this->baseInvoiceRepository->create(array_merge($data, ['order_id' => $orderId]));

        session()->flash('success', trans('marketplace::app.shop.sellers.account.orders.invoices.invoice-success'));

        return Back();
    }

    /**
     * Print and download the for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $sellerInvoice = $this->invoiceRepository->findOrFail($id);

        $pdf = PDF::loadView('marketplace::shop.sellers.account.orders.invoices.pdf', compact('sellerInvoice'))->setPaper('a4');

        return $pdf->download('invoice-'.$sellerInvoice->created_at->format('d-m-Y').'.pdf');
    }
}
