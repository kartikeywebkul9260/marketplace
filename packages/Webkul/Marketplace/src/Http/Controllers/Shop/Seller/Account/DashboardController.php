<?php

namespace Webkul\Marketplace\Http\Controllers\Shop\Seller\Account;

use Webkul\Marketplace\Helpers\Dashboard;
use Webkul\Marketplace\Http\Controllers\Shop\Controller;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected Dashboard $dashboardHelper)
    {
    }

    /**
     * Request param functions
     *
     * @var array
     */
    protected $typeFunctions = [
        'over-all'        => 'getOverAllStats',
        'total-sales'     => 'getSalesStats',
        'total-visitors'  => 'getVisitorStats',
        'recent-orders'   => 'getRecentOrders',
        'stock-threshold' => 'getStockThreshold',
        'top-products'    => 'getTopProducts',
        'top-customers'   => 'getTopCustomers',
        'top-categories'  => 'getTopCategories',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seller = auth()->guard('seller')->user();

        return view('marketplace::shop.sellers.account.dashboard.index')
            ->with([
                'seller'    => $seller,
                'startDate' => $this->dashboardHelper->getStartDate(),
                'endDate'   => $this->dashboardHelper->getEndDate(),
            ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats()
    {
        $stats = $this->dashboardHelper->
            {$this->typeFunctions[request()->query('type')]}(auth()->guard('seller')->user());

        return response()->json([
            'statistics' => $stats,
            'date_range' => $this->dashboardHelper->getDateRange(),
        ]);
    }
}
