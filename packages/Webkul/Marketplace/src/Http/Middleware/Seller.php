<?php

namespace Webkul\Marketplace\Http\Middleware;

use Closure;

class Seller
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'seller')
    {
        if (! auth()->guard($guard)->check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => '',
                ], 401);
            }

            return redirect()->route('marketplace.seller.session.index');
        } else {
            if (! auth()->guard($guard)->user()->is_approved) {
                auth()->guard($guard)->logout();

                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => trans('marketplace::app.shop.sellers.account.login.not-approved'),
                    ], 401);
                }

                session()->flash('warning', trans('marketplace::app.shop.sellers.account.login.not-approved'));

                return redirect()->route('marketplace.seller.session.index');
            }
        }

        return $next($request);
    }
}
