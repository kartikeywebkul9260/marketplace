<?php

namespace Webkul\Marketplace\DataGrids\Admin;

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

        $queryBuilder = DB::table('marketplace_products')
            ->leftJoin('product_flat', 'marketplace_products.product_id', '=', 'product_flat.product_id')
            ->leftJoin('products', 'product_flat.product_id', '=', 'products.id')
            ->leftJoin('marketplace_sellers', 'marketplace_products.marketplace_seller_id', '=', 'marketplace_sellers.id')
            ->leftJoin('product_images', 'product_flat.product_id', '=', 'product_images.product_id')
            ->leftJoin('marketplace_product_images', 'marketplace_products.id', '=', 'marketplace_product_images.marketplace_product_id')
            ->leftJoin('product_inventories', function ($join) {
                $join->on('marketplace_sellers.id', '=', 'product_inventories.vendor_id');
                $join->on('product_inventories.product_id', '=', 'marketplace_products.product_id');
            })
            ->addSelect('product_inventories.qty as quantity')->groupBy('marketplace_products.id')
            ->addSelect(
                'marketplace_products.id as marketplace_product_id',
                'product_flat.product_id',
                'product_flat.sku',
                'product_flat.url_key',
                'product_flat.visible_individually',
                'product_flat.name as product_flat_name',
                'products.type as product_type',
                'product_flat.product_number',
                'marketplace_products.is_owner',
                'marketplace_products.is_approved',
                'marketplace_sellers.name as seller_name',

                DB::raw('(CASE WHEN '.$tablePrefix.'marketplace_products.is_owner = 1 THEN '.$tablePrefix.'product_images.path ELSE '.$tablePrefix.'marketplace_product_images.path END) AS base_image'),

                DB::raw('(CASE WHEN '.$tablePrefix.'marketplace_products.is_owner = 1 THEN COUNT(DISTINCT '.$tablePrefix.'product_images.id) ELSE COUNT(DISTINCT '.$tablePrefix.'marketplace_product_images.id) END) AS images_count'),

                DB::raw('(CASE WHEN '.$tablePrefix.'marketplace_products.is_owner = 1 THEN '.$tablePrefix.'product_flat.price ELSE '.$tablePrefix.'marketplace_products.price END) AS price')
            )->where('channel', core()->getCurrentChannelCode())
            ->where('locale', app()->getLocale());

        $this->addFilter('sku', 'product_flat.sku');
        $this->addFilter('product_id', 'product_flat.product_id');
        $this->addFilter('product_number', 'product_flat.product_number');
        $this->addFilter('product_flat_name', 'product_flat.name');
        $this->addFilter('product_type', 'products.type');
        $this->addFilter('seller_name', 'marketplace_sellers.name');
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
            'index'      => 'product_flat_name',
            'label'      => trans('marketplace::app.admin.products.index.datagrid.product-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if (
                    ! empty($row->visible_individually)
                    && ! empty($row->url_key)
                ) {
                    return "<a href='".route('shop.product_or_category.index', $row->url_key)."' target='_blank'>".$row->product_flat_name.'</a>';
                }

                return $row->product_flat_name;
            },
        ]);

        $this->addColumn([
            'index'      => 'sku',
            'label'      => trans('marketplace::app.admin.products.index.datagrid.sku'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_type',
            'label'      => trans('marketplace::app.admin.products.index.datagrid.product-type'),
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
            'sortable'   => true,
            'searchable' => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'product_number',
            'label'      => trans('marketplace::app.admin.products.index.datagrid.product-number'),
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
            'label'      => trans('marketplace::app.admin.products.index.datagrid.price'),
            'type'       => 'price',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'quantity',
            'label'      => trans('marketplace::app.admin.products.index.datagrid.quantity'),
            'type'       => 'number',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'product_id',
            'label'      => trans('marketplace::app.admin.products.index.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'seller_name',
            'label'      => trans('marketplace::app.admin.products.index.datagrid.seller-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_owner',
            'label'      => trans('marketplace::app.admin.products.index.datagrid.is-owner'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label'  => trans('marketplace::app.admin.products.index.datagrid.yes'),
                            'value'  => 1,
                        ],
                        [
                            'label'  => trans('marketplace::app.admin.products.index.datagrid.no'),
                            'value'  => 0,
                        ],
                    ],
                ],
            ],
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if ($row->is_owner) {
                    return '<p class="label-active">'.trans('marketplace::app.admin.products.index.datagrid.yes').'</p>';
                }

                return '<p class="label-info">'.trans('marketplace::app.admin.products.index.datagrid.no').'</p>';
            },
        ]);

        $this->addColumn([
            'index'      => 'is_approved',
            'label'      => trans('marketplace::app.admin.products.index.datagrid.status'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label'  => trans('marketplace::app.admin.products.index.datagrid.approved'),
                            'value'  => 1,
                        ],
                        [
                            'label'  => trans('marketplace::app.admin.products.index.datagrid.disapproved'),
                            'value'  => 0,
                        ],
                    ],
                ],
            ],
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
            'closure'    => function ($row) {
                if ($row->is_approved) {
                    return '<p class="label-active">'.trans('marketplace::app.admin.products.index.datagrid.approved').'</p>';
                }

                return '<p class="label-info">'.trans('marketplace::app.admin.products.index.datagrid.disapproved').'</p>';
            },
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('marketplace.products.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('marketplace::app.admin.products.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.marketplace.products.delete', $row->marketplace_product_id);
                },
            ]);
        }
    }

    /**
     * Prepare mass actions.
     *
     * @return void
     */
    public function prepareMassActions()
    {
        if (bouncer()->hasPermission('marketplace.products.mass-update')) {
            $this->addMassAction([
                'title'   => trans('marketplace::app.admin.products.index.datagrid.update-status'),
                'url'     => route('admin.marketplace.products.mass_update'),
                'method'  => 'POST',
                'options' => [
                    [
                        'label' => trans('marketplace::app.admin.products.index.datagrid.approved'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('marketplace::app.admin.products.index.datagrid.disapproved'),
                        'value' => 0,
                    ],
                ],
            ]);
        }

        if (bouncer()->hasPermission('marketplace.products.mass-delete')) {
            $this->addMassAction([
                'title'  => trans('marketplace::app.admin.products.index.datagrid.delete'),
                'url'    => route('admin.marketplace.products.mass_delete'),
                'method' => 'POST',
            ]);
        }
    }
}
