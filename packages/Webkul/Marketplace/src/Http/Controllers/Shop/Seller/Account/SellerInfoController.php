<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account;

use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;
use Webkul\Marketplace\Repositories\SellerCategoryRepository;
use Webkul\Marketplace\Repositories\SellerRepository;

class SellerInfoController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Marketplace\Repositories\SellerRepository  $sellerRepository
     * @return void
     */
    public function __construct(
        protected SellerRepository $sellerRepository,
        protected SellerCategoryRepository $sellerCategoryRepository,
        protected CategoryRepository $categoryRepository,
    ) {
    }

    /**
     * Method to populate the seller review page which will be populated.
     *
     * @return mixed
     */
    public function index()
    {
        $allowedCategories = [];

        $seller = auth()->guard('seller')->user();

        if ($seller->categories?->categories) {
            $allowedCategories = $this->categoryRepository->findWhereIn('id', $seller->categories->categories);
        }

        $seller->allowed_product_types = $this->sellerRepository->getAllowedProducts($seller);

        return view('marketplace::shop.sellers.account.seller-info.index', ['seller' => $seller, 'allowedCategories' => $allowedCategories]);
    }
}
