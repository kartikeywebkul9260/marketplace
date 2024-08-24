<?php

namespace Webkul\Marketplace\DataGrids\Shop;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ProductDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'marketplace_product_id';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('product_flat')
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->join('marketplace_products', 'product_flat.product_id', '=', 'marketplace_products.product_id')
            ->leftJoin('marketplace_sellers', 'marketplace_products.marketplace_seller_id', '=', 'marketplace_sellers.id')
            ->leftJoin('product_images', 'product_flat.product_id', '=', 'product_images.product_id')
            ->leftJoin('marketplace_product_images', 'marketplace_products.id', '=', 'marketplace_product_images.marketplace_product_id')
            ->leftJoin('product_categories as pc', 'product_flat.product_id', '=', 'pc.product_id')
            ->leftJoin('category_translations as ct', function ($leftJoin) {
                $leftJoin->on('pc.category_id', '=', 'ct.category_id')
                    ->where('ct.locale', core()->getRequestedLocaleCode());
            })

            ->addSelect(
                'marketplace_products.id as marketplace_product_id',
                'products.type as product_type',
                'product_flat.product_id',
                'product_flat.status',
                'pc.category_id',
                'ct.name as category_name',
                'product_flat.sku',
                'product_flat.name as name',
                'product_flat.product_number',
                'marketplace_products.is_owner',
                'marketplace_products.is_approved',

                DB::raw('(CASE WHEN '.$tablePrefix.'marketplace_products.is_owner = 1 THEN '.$tablePrefix.'product_images.path ELSE '.$tablePrefix.'marketplace_product_images.path END) AS base_image'),

                DB::raw('(CASE WHEN '.$tablePrefix.'marketplace_products.is_owner = 1 THEN COUNT(DISTINCT '.$tablePrefix.'product_images.id) ELSE COUNT(DISTINCT '.$tablePrefix.'marketplace_product_images.id) END) AS images_count'),

                DB::raw('(CASE WHEN '.$tablePrefix.'marketplace_products.is_owner = 1 THEN '.$tablePrefix.'product_flat.price ELSE '.$tablePrefix.'marketplace_products.price END) AS price')
            )
            ->where('marketplace_products.marketplace_seller_id', auth()->guard('seller')->user()->id)
            ->distinct();

        $queryBuilder->leftJoin('product_inventories', function ($join) {
            $join->on('marketplace_sellers.id', '=', 'product_inventories.vendor_id');
            $join->on('product_inventories.product_id', '=', 'marketplace_products.product_id')
                ->where('product_inventories.vendor_id', '=', auth()->guard('seller')->user()->id);
        });

        $queryBuilder->addSelect('product_inventories.qty as quantity')
            ->groupBy('marketplace_products.id');

        $queryBuilder->where('product_flat.locale', core()->getRequestedLocaleCode());
        $queryBuilder->where('product_flat.channel', core()->getRequestedChannelCode());

        $this->addFilter('sku', 'product_flat.sku');
        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('product_number', 'product_flat.product_number');
        $this->addFilter('product_type', 'products.type');
        $this->addFilter('name', 'product_flat.name');
        $this->addFilter('status', 'product_flat.status');
        $this->addFilter('is_approved', 'marketplace_products.is_approved');
        $this->addFilter('price', DB::raw('(CASE WHEN marketplace_products.is_owner = 1 THEN product_flat.price ELSE marketplace_products.price END)'));

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
            'index'      => 'name',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'sku',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.sku'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_number',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.product-number'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'base_image',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.image'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
        ]);

        $this->addColumn([
            'index'      => 'price',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.price'),
            'type'       => 'string',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($row) {
                return core()->formatBasePrice($row->price);
            },
        ]);

        $this->addColumn([
            'index'      => 'quantity',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.stock'),
            'type'       => 'number',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'product_id',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.status'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label'  => trans('marketplace::app.shop.sellers.account.products.index.datagrid.active'),
                            'value'  => 1,
                        ],
                        [
                            'label'  => trans('marketplace::app.shop.sellers.account.products.index.datagrid.disable'),
                            'value'  => 0,
                        ],
                    ],
                ],
            ],
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_approved',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.is-approved'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label'  => trans('marketplace::app.shop.sellers.account.products.index.datagrid.approved'),
                            'value'  => 1,
                        ],
                        [
                            'label'  => trans('marketplace::app.shop.sellers.account.products.index.datagrid.disapproved'),
                            'value'  => 0,
                        ],
                    ],
                ],
            ],
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_type',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.type'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => collect(config('product_types'))
                        ->map(fn ($type) => ['label' => trans($type['name']), 'value' => $type['key']])
                        ->values()
                        ->toArray(),
                ],
            ],
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'category_name',
            'label'      => trans('marketplace::app.shop.sellers.account.products.index.datagrid.category'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => false,
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
            'type'      => 'Edit',
            'method'    => 'GET',
            'url'       => function ($row) {
                return route('marketplace.account.products.edit', $row->product_id);
            },
            'icon'      => 'icon-arrow-right',
            'title'     => trans('marketplace::app.shop.sellers.account.products.index.datagrid.edit'),
            'condition' => function ($row) {
                if (
                    $row->product_type == 'configurable'
                    && $row->is_owner == 0
                ) {
                    return false;
                }

                return true;
            },
        ], true);

        $this->addAction([
            'type'      => 'Delete',
            'method'    => 'DELETE',
            'url'       => function ($row) {
                return route('marketplace.account.products.delete', $row->marketplace_product_id);
            },
            'icon'      => 'mp-delete-icon',
            'title'     => trans('marketplace::app.shop.sellers.account.products.index.datagrid.delete'),
            'condition' => function ($row) {
                if (
                    $row->product_type == 'configurable'
                    && $row->is_owner == 0
                ) {
                    return false;
                }

                return true;
            },
        ], true);
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        $this->addMassAction([
            'title'  => trans('marketplace::app.shop.sellers.account.products.index.datagrid.delete'),
            'url'    => route('marketplace.account.products.mass_delete'),
            'method' => 'POST',
        ]);
    }
}
