<?php

namespace Webkul\Marketplace\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Webkul\Admin\Http\Requests\MassUpdateRequest;
use Webkul\Marketplace\DataGrids\Admin\SellerReviewDataGrid;
use Webkul\Marketplace\Repositories\ProductRepository;
use Webkul\Marketplace\Repositories\ReviewRepository;
use Webkul\Product\Repositories\ProductFlatRepository;

class SellerReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ReviewRepository $reviewRepository,
        protected ProductRepository $productRepository,
        protected ProductFlatRepository $productFlatRepository,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(SellerReviewDataGrid::class)->toJson();
        }

        return view('marketplace::admin.seller-reviews.index');
    }

    /**
     * Mass update the reviews.
     */
    public function massUpdate(MassUpdateRequest $request): JsonResponse
    {
        $this->reviewRepository->whereIn('id', $request->input('indices'))->update([
            'status' => $request->input('value'),
        ]);

        return new JsonResponse([
            'message' => trans('marketplace::app.admin.seller-reviews.index.update-success'),
        ], 200);
    }
}
