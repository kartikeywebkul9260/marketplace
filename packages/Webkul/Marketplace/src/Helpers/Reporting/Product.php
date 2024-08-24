<?php

namespace Webkul\Marketplace\Helpers\Reporting;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Webkul\Admin\Helpers\Reporting\AbstractReporting;
use Webkul\Category\Repositories\CategoryRepository;
use Webkul\Marketplace\Repositories\OrderItemRepository;
use Webkul\Product\Repositories\ProductInventoryRepository;

class Product extends AbstractReporting
{
    /**
     * Create a helper instance.
     *
     * @return void
     */
    public function __construct(
        protected CategoryRepository $categoryRepository,
        protected ProductInventoryRepository $productInventoryRepository,
        protected OrderItemRepository $orderItemRepository,
    ) {
        parent::__construct();
    }

    /**
     * Gets stock threshold.
     *
     * @param  int  $limit
     */
    public function getStockThresholdProducts($seller, $limit = 5): Collection
    {
        return $this->productInventoryRepository->getModel()
            ->with([
                'product' => [
                    'images',
                ],
            ])
            ->leftJoin('products', 'product_inventories.product_id', 'products.id')
            ->leftJoin('marketplace_products', 'products.id', 'marketplace_products.product_id')
            ->select('marketplace_products.*')
            ->addSelect(DB::raw('SUM(qty) as total_qty'))
            ->where('products.type', '!=', 'configurable')
            ->where('marketplace_products.marketplace_seller_id', $seller->id)
            ->where('product_inventories.vendor_id', $seller->id)
            ->groupBy('marketplace_products.product_id')
            ->orderBy('total_qty', 'ASC')
            ->limit($limit)
            ->get();
    }

    /**
     * Gets top-selling products by revenue.
     *
     * @param  int  $limit
     */
    public function getTopSellingProductsByRevenue($seller, $limit = 5): collection
    {
        return $this->orderItemRepository->getModel()
            ->with([
                'item' => [
                    'product' => [
                        'images',
                    ],
                ],
            ])
            ->join('order_items', 'order_items.id', 'marketplace_order_items.order_item_id')
            ->join('products', 'products.id', 'order_items.product_id')
            ->leftJoin('marketplace_orders', 'marketplace_order_items.marketplace_order_id', 'marketplace_orders.id')
            ->addSelect(
                '*',
                DB::raw('SUM(base_total_invoiced - order_items.base_discount_refunded) as revenue'),
                DB::raw('SUM(base_total_invoiced - order_items.base_discount_refunded) / SUM(order_items.qty_invoiced - order_items.qty_refunded) as per_unit')
            )
            ->whereNull('order_items.parent_id')
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->groupBy('order_items.product_id')
            ->orderBy('revenue', 'DESC')
            ->limit($limit)
            ->get();
    }

    /**
     * Gets top-selling categories by revenue.
     *
     * @param  int  $limit
     */
    public function getTopSellingCategoriesByRevenue($seller, $limit = 5): collection
    {
        return $this->orderItemRepository->getModel()
            ->join('order_items', 'order_items.id', 'marketplace_order_items.order_item_id')
            ->leftJoin('marketplace_orders', 'marketplace_order_items.marketplace_order_id', 'marketplace_orders.id')
            ->leftJoin('product_categories', 'product_categories.product_id', 'order_items.product_id')
            ->leftJoin('category_translations', 'category_translations.category_id', 'product_categories.category_id')
            ->addSelect(
                'category_translations.*',
                DB::raw('SUM(base_total_invoiced - order_items.base_discount_refunded) as revenue')
            )
            ->where('product_categories.category_id', '!=', 1)
            ->whereNull('order_items.parent_id')
            ->where('category_translations.locale', app()->getLocale())
            ->where('marketplace_orders.marketplace_seller_id', $seller->id)
            ->whereBetween('order_items.created_at', [$this->startDate, $this->endDate])
            ->groupBy('product_categories.category_id')
            ->orderBy('revenue', 'DESC')
            ->limit($limit)
            ->get();
    }
}
