<?php

namespace Webkul\Marketplace\Http\Controllers\Shop;

use Illuminate\Http\JsonResponse;
use Webkul\Marketplace\Enum\Review;
use Webkul\Marketplace\Repositories\ReviewRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        protected ReviewRepository $reviewRepository,
        protected SellerRepository $sellerRepository
    ) {
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $url
     * @return mixed
     */
    public function index($url)
    {
        $seller = $this->sellerRepository->findByUrlOrFail($url);

        $reviews = $this->reviewRepository
            ->with('customer')
            ->where('marketplace_seller_id', $seller->id)
            ->paginate(5);

        return view('marketplace::shop.sellers.reviews', ['seller' => $seller, 'reviews' => $reviews]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(): JsonResponse
    {
        $this->validate(request(), [
            'rating'  => 'required',
            'title'   => 'required',
            'comment' => 'required',
        ]);

        if (! auth()->guard('customer')->user()) {
            return new JsonResponse([
                'message' => trans('marketplace::app.shop.sellers.profile.login-first'),
            ]);
        }

        $seller = $this->sellerRepository->findByUrlOrFail(request()->input('seller_url'));

        $this->reviewRepository->create(array_merge(request()->only([
            'rating',
            'title',
            'comment',
        ]), [
            'status'                => Review::STATUS_PENDING->value,
            'marketplace_seller_id' => $seller->id,
            'customer_id'           => auth()->guard('customer')->user()->id,
        ]));

        return new JsonResponse([
            'message' => trans('marketplace::app.shop.sellers.profile.review-successfull'),
        ]);
    }
}
