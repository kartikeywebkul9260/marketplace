<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;
use Webkul\Marketplace\Enum\Review;

class ProductReviewsDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'product_review_id';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('product_reviews as pr')
            ->leftJoin('product_flat as pf', 'pr.product_id', '=', 'pf.product_id')
            ->leftJoin('customers as c', 'pr.customer_id', '=', 'c.id')
            ->leftJoin('marketplace_products as mp', 'mp.product_id', '=', 'pf.product_id')
            ->leftJoin('marketplace_sellers as ms', 'ms.id', '=', 'mp.marketplace_seller_id')
            ->select(
                'pr.id as product_review_id',
                'pr.title',
                'pr.comment',
                'pr.name as customer_full_name',
                'pf.name as product_name',
                'pr.status as product_review_status',
                'pr.rating',
                'pr.created_at',
                'ms.shop_title',
            )
            ->where('channel', core()->getCurrentChannelCode())
            ->where('locale', app()->getLocale());

        $this->addFilter('product_review_id', 'pr.id');
        $this->addFilter('customer_full_name', 'pr.name');
        $this->addFilter('product_review_status', 'pr.status');
        $this->addFilter('product_name', 'pf.name');
        $this->addFilter('created_at', 'pr.created_at');
        $this->addFilter('shop_title', 'ms.shop_title');

        return $queryBuilder;
    }

    /**
     * Prepare columns.
     *
     * @return void
     */
    public function prepareColumns()
    {
        $this->addColumn([
            'index'      => 'customer_full_name',
            'label'      => trans('marketplace::app.admin.product-reviews.index.datagrid.customer-name'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'product_name',
            'label'      => trans('marketplace::app.admin.product-reviews.index.datagrid.product-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_review_status',
            'label'      => trans('marketplace::app.admin.product-reviews.index.datagrid.status'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label'  => trans('marketplace::app.admin.product-reviews.index.datagrid.approved'),
                            'value'  => Review::STATUS_APPROVED->value,
                        ],
                        [
                            'label'  => trans('marketplace::app.admin.product-reviews.index.datagrid.pending'),
                            'value'  => Review::STATUS_PENDING->value,
                        ],
                        [
                            'label'  => trans('marketplace::app.admin.product-reviews.index.datagrid.disapproved'),
                            'value'  => Review::STATUS_DISAPPROVED->value,
                        ],
                    ],
                ],
            ],
            'searchable' => true,
            'sortable'   => true,
            'width'      => '100px',
            'filterable' => true,
            'closure'    => function ($row) {
                switch ($row->product_review_status) {
                    case Review::STATUS_PENDING->value:
                        return '<p class="label-pending">'.trans('marketplace::app.admin.product-reviews.index.datagrid.pending').'</p>';

                    case Review::STATUS_APPROVED->value:
                        return '<p class="label-active">'.trans('marketplace::app.admin.product-reviews.index.datagrid.approved').'</p>';

                    case Review::STATUS_DISAPPROVED->value:
                        return '<p class="label-canceled">'.trans('marketplace::app.admin.product-reviews.index.datagrid.disapproved').'</p>';
                }
            },
        ]);

        $this->addColumn([
            'index'      => 'rating',
            'label'      => trans('marketplace::app.admin.product-reviews.index.datagrid.rating'),
            'type'       => 'number',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('marketplace::app.admin.product-reviews.index.datagrid.date'),
            'type'       => 'date_range',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_review_id',
            'label'      => trans('marketplace::app.admin.product-reviews.index.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'shop_title',
            'label'      => trans('marketplace::app.admin.product-reviews.index.datagrid.shop-title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'title',
            'label'      => trans('marketplace::app.admin.product-reviews.index.datagrid.title'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'comment',
            'label'      => trans('marketplace::app.admin.product-reviews.index.datagrid.comment'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        $this->addAction([
            'icon'   => 'icon-view',
            'title'  => trans('marketplace::app.admin.product-reviews.index.datagrid.view'),
            'method' => 'GET',
            'url'    => function ($row) {
                return route('admin.customers.customers.review.edit', $row->product_review_id);
            },
        ]);
    }
}
