<?php

namespace Webkul\Marketplace\DataGrids\Admin;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class SellerFlagDataGrid extends DataGrid
{
    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('marketplace_seller_flags')
            ->select(
                'marketplace_seller_flags.id',
                'marketplace_seller_flags.reason',
                'marketplace_seller_flags.name',
                'marketplace_seller_flags.email'
            )
            ->where('marketplace_seller_flags.seller_id', request()->id);

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
            'index'      => 'id',
            'label'      => trans('marketplace::app.admin.sellers.edit.datagrid.id'),
            'type'       => 'number',
            'searchable' => false,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('marketplace::app.admin.sellers.edit.datagrid.customer-name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('marketplace::app.admin.sellers.edit.datagrid.customer-email'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'reason',
            'label'      => trans('marketplace::app.admin.sellers.edit.datagrid.reason'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);
    }
}
