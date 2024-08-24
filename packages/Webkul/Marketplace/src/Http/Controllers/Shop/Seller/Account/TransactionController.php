<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account;

use Barryvdh\DomPDF\Facade\Pdf;
use Webkul\Marketplace\DataGrids\Shop\TransactionDataGrid;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\OrderRepository;
use Webkul\Marketplace\Repositories\SellerRepository;
use Webkul\Marketplace\Repositories\TransactionRepository;

class TransactionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected OrderRepository $orderRepository,
        protected TransactionRepository $transactionRepository
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(TransactionDataGrid::class)->toJson();
        }

        return view('marketplace::shop.sellers.account.transactions.index')
            ->with([
                'statistics' => $this->transactionRepository->statistics(auth()->guard('seller')->user()),
            ]);
    }

    /**
     * Show the view for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $transaction = $this->transactionRepository->with('order')->findOneWhere([
            'id'  => $id,
            'marketplace_seller_id' => auth()->guard('seller')->user()->id,
        ]);

        return view('marketplace::shop.sellers.account.transactions.view', compact('transaction'));
    }

    /**
     * Print and download the for the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function print($id)
    {
        $transaction = $this->transactionRepository->with('order')->findOneWhere([
            'id'                    => $id,
            'marketplace_seller_id' => auth()->guard('seller')->user()->id,
        ]);

        $pdf = PDF::loadView('marketplace::shop.sellers.account.transactions.pdf', compact('transaction'))->setPaper('a4');

        return $pdf->download('transaction-'.$transaction->created_at->format('d-m-Y').'.pdf');
    }
}
