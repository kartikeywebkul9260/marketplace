<?php

namespace Webkul\Marketplace\DataGrids\Shop;

use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class CustomerDataGrid extends DataGrid
{
    /**
     * Primary column.
     *
     * @var string
     */
    protected $primaryColumn = 'customer_id';

    /**
     * Prepare query builder.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function prepareQueryBuilder()
    {
        $tablePrefix = DB::getTablePrefix();

        $queryBuilder = DB::table('customers')
            ->leftJoin('addresses', function ($join) {
                $join->on('customers.id', '=', 'addresses.customer_id')
                    ->where('addresses.address_type', '=', 'customer');
            })
            ->addSelect('customers.id as customer_id')
            ->addSelect(DB::raw('COUNT(DISTINCT '.$tablePrefix.'addresses.id) as address_count'))
            ->groupBy('customers.id')

            ->leftJoin('orders', function ($join) {
                $join->on('customers.id', '=', 'orders.customer_id');
            })
            ->leftJoin('marketplace_orders', 'marketplace_orders.order_id', '=', 'orders.id')
            ->addSelect('customers.id as customer_id')
            ->addSelect(DB::raw('COUNT(DISTINCT '.$tablePrefix.'orders.id) as order_count'))
            ->groupBy('customers.id')

            ->leftJoin('customer_groups', 'customers.customer_group_id', '=', 'customer_groups.id')
            ->addSelect(
                'customers.id as customer_id',
                'customers.email',
                'customers.phone',
                'customers.gender',
                'customers.status',
                'customers.is_suspended',
                'customer_groups.name as group',
            )
            ->addSelect(
                DB::raw('CONCAT('.$tablePrefix.'customers.first_name, " ", '.$tablePrefix.'customers.last_name) as full_name'),
                DB::raw('SUM(CASE WHEN marketplace_orders.status NOT IN ("canceled", "closed") THEN marketplace_orders.grand_total_invoiced ELSE 0 END) as revenue')
            )
            ->where('marketplace_orders.marketplace_seller_id', auth()->guard('seller')->user()->id);

        $this->addFilter('email', 'customers.email');
        $this->addFilter('full_name', DB::raw('CONCAT('.$tablePrefix.'customers.first_name, " ", '.$tablePrefix.'customers.last_name)'));
        $this->addFilter('group', 'customer_groups.name');
        $this->addFilter('status', 'customers.status');

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
            'index'      => 'full_name',
            'label'      => trans('marketplace::app.shop.sellers.account.customers.index.datagrid.customer'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'email',
            'label'      => trans('marketplace::app.shop.sellers.account.customers.index.datagrid.email'),
            'type'       => 'string',
            'searchable' => true,
            'filterable' => true,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'      => 'gender',
            'label'      => trans('marketplace::app.shop.sellers.account.customers.index.datagrid.gender'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => false,
            'sortable'   => true,
        ]);

        $this->addColumn([
            'index'       => 'revenue',
            'label'       => trans('marketplace::app.shop.sellers.account.customers.index.datagrid.revenue'),
            'type'        => 'integer',
            'searchable'  => false,
            'filterable'  => false,
            'sortable'    => false,
        ]);

        $this->addColumn([
            'index'       => 'order_count',
            'label'       => trans('marketplace::app.shop.sellers.account.customers.index.datagrid.order-count'),
            'type'        => 'integer',
            'searchable'  => false,
            'filterable'  => false,
            'sortable'    => true,
        ]);

        $this->addColumn([
            'index'      => 'status',
            'label'      => trans('marketplace::app.shop.sellers.account.customers.index.datagrid.status'),
            'type'       => 'dropdown',
            'options'    => [
                'type' => 'basic',

                'params' => [
                    'options' => [
                        [
                            'label'  => trans('marketplace::app.shop.sellers.account.customers.index.datagrid.active'),
                            'value'  => 1,
                        ],
                        [
                            'label'  => trans('marketplace::app.shop.sellers.account.customers.index.datagrid.inactive'),
                            'value'  => 0,
                        ],
                    ],
                ],
            ],
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
            'closure'    => function ($row) {
                if ($row->status) {
                    return '<span class="label-active">'.trans('marketplace::app.shop.sellers.account.customers.index.datagrid.active').'</span>';
                }

                return '<span class="label-info">'.trans('marketplace::app.shop.sellers.account.customers.index.datagrid.inactive').'</span>';
            },
        ]);

        $this->addColumn([
            'index'      => 'group',
            'label'      => trans('marketplace::app.shop.sellers.account.customers.index.datagrid.group'),
            'type'       => 'string',
            'searchable' => false,
            'filterable' => true,
            'sortable'   => false,
        ]);
    }
}
