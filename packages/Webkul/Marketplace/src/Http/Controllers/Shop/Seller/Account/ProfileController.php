<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account;

use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Http\Requests\SellerFormRequest;
use Webkul\Marketplace\Repositories\SellerRepository;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected SellerRepository $sellerRepository)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('marketplace::shop.sellers.account.profile.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SellerFormRequest $request, $id)
    {
        $this->sellerRepository->update($request->validated(), $id);

        session()->flash('success', trans('marketplace::app.shop.sellers.account.manage-profile.update-success'));

        return Back();
    }
}
