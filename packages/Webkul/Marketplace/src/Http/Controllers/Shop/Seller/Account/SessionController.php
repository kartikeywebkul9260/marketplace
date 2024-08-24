<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account;

use Webkul\Marketplace\Http\Controllers\Shop\Controller;

class SessionController extends Controller
{
    /**
     * Display the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function show()
    {
        return auth()->guard('seller')->check()
            ? redirect()->route('shop.marketplace.seller.account.dashboard.index')
            : view('marketplace::shop.default.sellers.account.sign-in');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (! auth()->guard('seller')->attempt(request()->only(['email', 'password']))) {
            session()->flash('error', trans('marketplace::app.shop.sellers.account.login.invalid-credentials'));

            return redirect()->back();
        }

        if (! auth()->guard('seller')->user()->is_approved) {
            session()->flash('info', trans('marketplace::app.shop.sellers.account.login.not-approved'));

            auth()->guard('seller')->logout();

            return redirect()->back();
        }

        return redirect()->route('shop.marketplace.seller.account.dashboard.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        auth()->guard('seller')->logout();

        return redirect()->route('marketplace.seller.session.index');
    }
}
