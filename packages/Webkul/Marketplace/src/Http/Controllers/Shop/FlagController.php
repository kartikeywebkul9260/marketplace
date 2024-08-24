<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;
use Webkul\Marketplace\Mail\AdminReportSellerNotification;
use Webkul\Marketplace\Mail\ReportSellerNotification;
use Webkul\Marketplace\Repositories\SellerFlagRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class FlagController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected SellerFlagRepository $sellerFlagRepository,
        protected SellerRepository $sellerRepository
    ) {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'name'   => 'required',
            'reason' => 'required',
            'email'  => 'required|unique:marketplace_seller_flags',
        ]);

        if (! auth()->guard('customer')->user()) {
            return new JsonResponse([
                'message' => trans('marketplace::app.shop.sellers.profile.login-first'),
            ]);
        }

        $seller = $this->sellerRepository->findByUrlOrFail(request()->input('seller_url'));

        $this->sellerFlagRepository->create(array_merge(request()->only([
            'name',
            'email',
            'reason',
        ]), [
            'seller_id' => $seller->id,
        ]));

        try {
            Mail::queue(new ReportSellerNotification($seller, request()->input()));

            Mail::to(core()->getAdminEmailDetails()['email'])
                ->send(new AdminReportSellerNotification($seller, request()->input()));
        } catch (\Exception $e) {
        }

        return new JsonResponse([
            'message' => trans('marketplace::app.shop.sellers.profile.reported-successfull'),
        ]);
    }
}
