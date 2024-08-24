<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    /**
     * Display the password reset view for the given token.
     *
     * If no token is present, display the link request form.
     *
     * @param  string|null  $token
     * @return \Illuminate\View\View
     */
    public function create($token = null)
    {
        return view('marketplace::shop.default.sellers.account.reset-password')->with([
            'token' => $token,
            'email' => request('email'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        try {
            $this->validate(request(), [
                'token'    => 'required',
                'email'    => 'required|email',
                'password' => 'required|confirmed|min:6',
            ]);

            $response = $this->broker()->reset(
                request(['email', 'password', 'password_confirmation', 'token']), function ($seller, $password) {
                    $this->resetPassword($seller, $password);
                }
            );

            if ($response == Password::PASSWORD_RESET) {
                return redirect()->route('marketplace.seller.session.index');
            }

            return back()
                ->withInput(request(['email']))
                ->withErrors([
                    'email' => trans($response),
                ]);
        } catch (\Exception $e) {
            session()->flash('error', trans($e->getMessage()));

            return redirect()->back();
        }
    }

    /**
     * Reset the given seller password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $seller
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($seller, $password)
    {
        $seller->password = Hash::make($password);

        $seller->save();

        event(new PasswordReset($seller));
    }

    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('seller');
    }
}
