<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SellerDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $table_prefix = DB::getTablePrefix();

        $queryBuilder = DB::table('marketplace_sellers')
            ->leftJoin('marketplace_seller_flags', 'marketplace_sellers.id', '=', 'marketplace_seller_flags.seller_id')
            ->addSelect(
                'marketplace_sellers.id',
                'marketplace_sellers.created_at',
                'marketplace_sellers.email',
                'marketplace_sellers.is_approved',
                'marketplace_sellers.name',
                'marketplace_sellers.url',
            )
            ->selectRaw(
                'count('.$table_prefix.'marketplace_seller_flags.id) as flags')->groupBy('marketplace_sellers.id'
                );

        $this->addFilter('id', 'marketplace_sellers.id');
        $this->addFilter('name', 'marketplace_sellers.name');
        $this->addFilter('email', 'marketplace_sellers.email');
        $this->addFilter('created_at', 'marketplace_sellers.created_at');

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
            'label'      => trans('marketplace::app.admin.sellers.index.datagrid.seller-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('marketplace::app.admin.sellers.index.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'url',
            'label'      => trans('marketplace::app.admin.sellers.index.datagrid.shop-url'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
        ]);

        $this->addColumn([
            'index'      => 'created_at',
            'label'      => trans('marketplace::app.admin.sellers.index.datagrid.created-at'),
            'type'       => 'date_range',
            'sortable'   => true,
            'searchable' => false,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'is_approved',
            'label'      => trans('marketplace::app.admin.sellers.index.datagrid.status'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label'  => trans('marketplace::app.admin.sellers.index.datagrid.approved'),
                            'value'  => 1,
                        ],
                        [
                            'label'  => trans('marketplace::app.admin.sellers.index.datagrid.disapproved'),
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
                    return '<p class="label-active">'.trans('marketplace::app.admin.sellers.index.datagrid.approved').'</p>';
                }

                return '<p class="label-info">'.trans('marketplace::app.admin.sellers.index.datagrid.disapproved').'</p>';
            },
        ]);

        $this->addColumn([
            'index'      => 'id',
            'label'      => trans('marketplace::app.admin.sellers.index.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        if (bouncer()->hasPermission('marketplace.sellers.assign-product')) {
            $this->addColumn([
                'index'      => 'assign_product',
                'label'      => trans('marketplace::app.admin.sellers.index.datagrid.assign-product'),
                'type'       => 'string',
                'sortable'   => false,
                'searchable' => false,
                'filterable' => false,
                'closure'    => function ($row) {
                    return '<a href= "'.route('admin.marketplace.sellers.products.search', $row->id).'" class="secondary-button">'.trans('marketplace::app.admin.sellers.index.datagrid.add-product').'</a>';
                },
            ]);
        }

        $this->addColumn([
            'index'      => 'flags',
            'label'      => trans('marketplace::app.admin.sellers.index.datagrid.flags'),
            'type'       => 'integer',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => false,
        ]);
    }

    /**
     * Prepare actions.
     *
     * @return void
     */
    public function prepareActions()
    {
        if (bouncer()->hasPermission('marketplace.sellers.edit')) {
            $this->addAction([
                'icon'   => 'icon-edit',
                'title'  => trans('marketplace::app.admin.sellers.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => function ($row) {
                    return route('admin.marketplace.sellers.edit', $row->id);
                },
            ]);
        }

        if (bouncer()->hasPermission('marketplace.sellers.delete')) {
            $this->addAction([
                'icon'   => 'icon-delete',
                'title'  => trans('marketplace::app.admin.sellers.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => function ($row) {
                    return route('admin.marketplace.sellers.delete', $row->id);
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
        if (bouncer()->hasPermission('marketplace.sellers.mass-update')) {
            $this->addMassAction([
                'title'   => trans('marketplace::app.admin.sellers.index.datagrid.update-status'),
                'url'     => route('admin.marketplace.sellers.mass_update'),
                'method'  => 'POST',
                'options' => [
                    [
                        'label' => trans('marketplace::app.admin.sellers.index.datagrid.approved'),
                        'value' => 1,
                    ],
                    [
                        'label' => trans('marketplace::app.admin.sellers.index.datagrid.disapproved'),
                        'value' => 0,
                    ],
                ],
            ]);
        }

        if (bouncer()->hasPermission('marketplace.sellers.mass-delete')) {
            $this->addMassAction([
                'title'  => trans('marketplace::app.admin.sellers.index.datagrid.delete'),
                'url'    => route('admin.marketplace.sellers.mass_delete'),
                'method' => 'POST',
            ]);
        }
    }
}
