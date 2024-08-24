<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account;

use Illuminate\Support\Facades\Mail;
use Webkul\Core\Rules\Slug;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Mail\NewSellerNotification;
use Webkul\Marketplace\Mail\SellerApprovalNotification;
use Webkul\Marketplace\Mail\SellerWelcomeNotification;
use Webkul\Marketplace\Repositories\SellerRepository;

class RegistrationController extends Controller
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
     * Opens up the user's sign up form.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('marketplace::shop.default.sellers.account.sign-up');
    }

    /**
     * Method to store user's sign up form data to DB.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $this->validate(request(), [
            'name'     => ['required'],
            'email'    => ['required', 'email', 'unique:marketplace_sellers,email'],
            'url'      => ['required', 'unique:marketplace_sellers,url', 'lowercase', new Slug],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $seller = $this->sellerRepository->create(array_merge(request()->only([
            'name',
            'email',
            'url',
        ]), [
            'password'              => bcrypt(request()->input('password')),
            'is_approved'           => ! core()->getConfigData('marketplace.settings.general.seller_approval_required'),
            'allowed_product_types' => [
                "simple",
                "configurable",
                "virtual",
                "downloadable",
            ],
        ]));

        try {
            if ($seller->is_approved) {
                Mail::queue(new SellerApprovalNotification($seller));
            }

            Mail::queue(new SellerWelcomeNotification($seller));

            Mail::to(core()->getAdminEmailDetails()['email'])
                ->send(new NewSellerNotification($seller));
        } catch (\Exception $e) {
        }

        session()->flash('success', trans('marketplace::app.shop.sellers.account.signup.success'));

        return redirect()->route('marketplace.seller.session.index');
    }
}
