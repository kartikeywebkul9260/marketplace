<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account;

use Webkul\Marketplace\DataGrids\Shop\ProductReviewsDataGrid;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;

class ProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ProductReviewsDataGrid::class)->toJson();
        }

        return view('marketplace::shop.sellers.account.product-reviews.index');
    }
}
