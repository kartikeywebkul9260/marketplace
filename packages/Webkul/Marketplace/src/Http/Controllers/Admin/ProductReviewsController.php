<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Webkul\Marketplace\DataGrids\Admin\ProductReviewsDataGrid;

class ProductReviewsController extends Controller
{
    /**
     * Products Reviews
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ProductReviewsDataGrid::class)->toJson();
        }

        return view('marketplace::admin.product-reviews.index');
    }
}
